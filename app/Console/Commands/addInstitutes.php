<?php

namespace App\Console\Commands;

use App\Contracts\Services\CurrentType\CurrentType;
use App\Models\FileType;
use Illuminate\Console\Command;
use App\Models\Sight;
use App\Models\Status;
use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class addInstitutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sights_save {page_institutes?} {limit_institutes?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get institutes';
    private array $types;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->getAndSetRubrics();
        $this->argument('page_institutes') > 1 ? $page_institutes = (int)$this->argument('page_institutes') : $page_institutes = 1;
        $this->argument("limit_institutes") >= 1 ? $limit_institutes = (int)$this->argument("limit_institutes") : $limit_institutes = 10;
        try
        {
            $this->downloadSight($page_institutes, $limit_institutes);
        }
        catch(Exception $e)
        {
            print($e);
            $this->downloadSight($page_institutes, $limit_institutes);
        }
    }

    private function downloadSight($page_institutes, $limit_institutes)
    {
        $type = FileType::where('name', 'image')->first();
        $status = Status::where('name', 'Опубликовано')->first();

        $sights = $this->getPageInstitutes($page_institutes, $limit_institutes);

        foreach ($sights->items as $sight) {
            if (!Sight::where('cult_id', $sight->_id)->first()) {
                DB::beginTransaction();
                try {
                    // Сохраняем место
                    $sight_cr = $this->saveSight($sight);
                    $stypes = $this->getType($sight);
                    if(!empty($stypes)){
                        foreach($stypes as $stype){
                            $sight_cr->types()->attach($stype['id']);
                        }
                    } else {
                        throw new Exception("Type not found");
                    }
                    // Подвязываем фото
                    if (isset($sight->thumbnailFile)) {
                        if (preg_match('/[a-z]+/i', $sight->thumbnailFile->publicId)) {
                            $sight_cr->files()->create([
                                "name" => $sight->thumbnailFile->originalName,
                                "link" => 'https://cdn.culture.ru/images/' . $sight->thumbnailFile->publicId . '/w_' . $sight->thumbnailFile->width . ',h_' . $sight->thumbnailFile->height . '/' . $sight->thumbnailFile->originalName,
                            ])->file_types()->sync($type->id);
                        } else {
                            $sight_cr->files()->create([
                                "name" => $sight->thumbnailFile->originalName,
                                "link" => 'https://cdn.culture.ru/c/' . $sight->thumbnailFile->publicId . '.' . $sight->thumbnailFile->width . 'x' . $sight->thumbnailFile->height . '.' . $sight->thumbnailFile->format,
                            ])->file_types()->sync($type->id);
                        }
                    }
                    // Ставим статус
                    $sight_cr->statuses()->updateExistingPivot($status, ['last' => false]);
                    $sight_cr->statuses()->attach($status, ['last' => true]);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }
    }
    private function saveSight(object $sight): Sight
    {
        $sight_cr = Sight::create([
            'name' => $sight->title,
            'sponsor' => $sight->passport->organization,
            'location_id' => Location::where('cult_id', $sight->locale->_id)->firstOrFail()->id ?? "",
            'address' => $sight->address,
            'latitude' => $sight->location->coordinates[1],
            'longitude' => $sight->location->coordinates[0],
            'description' => strip_tags(preg_replace('/\[HTML\]|\[\/HTML\]/', '', $sight->text)),
            'user_id' => 1,
            'cult_id' => $sight->_id,
            'work_time' => $sight->workTime,
        ]);
        $sight_cr->organization()->create();
        return $sight_cr;
    }
    private  function getType($sight): array
    {
        $types = [];
        foreach ($sight->rubrics as $rubric) {
            $path = explode('/', $rubric->path);
            foreach($this->types as $type) {
                if($path[0] == $type->path){
                    $current_type = (new CurrentType($type->title))->getType();
                    !in_array($current_type, $types) ? $types[] = $current_type : null;
                }
            }
        }
        return $types;
    }
    private function getPageInstitutes($page_institutes, $limit_institutes) {
        try
        {
            return json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes . '&statuses=published', true));
        }  catch (Exception $e) {
            Log::error('Ошибка при получении страницы events(page='.$page_institutes.', limit='.$limit_institutes.'): '.json_decode($e));
            sleep(5);
            $this->getPageInstitutes($page_institutes, $limit_institutes);
        }
    }
    private function getAndSetRubrics(): void
    {
        $response = json_decode(file_get_contents('https://www.culture.ru/api-next/rubrics?fields=title,level,path&sort=level&limit=25', true));
        foreach ($response->items as $rubric) {
            $rubric->level < 1 ? $this->types[] = $rubric : null;
        }
    }
}
