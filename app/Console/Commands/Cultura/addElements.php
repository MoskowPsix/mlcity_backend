<?php

namespace App\Console\Commands\Cultura;

use Illuminate\Console\Command;
use App\Models\SightType;
use App\Models\Sight;
use App\Models\Status;

class addElements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'culture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get elements culture.ru';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $page_institutes = 0;
        $limit_institutes = 10;
        $total_institutes = json_decode(file_get_contents('https://www.culture.ru/api/atlas/institutes?page='.$page_institutes.'&limit='.$limit_institutes, true))->pagination->total;
        $institutes_download = [];


        $limit_rubrics = 10;
        $page_rubrics = 1;
        $total_rubrics = json_decode(file_get_contents('https://www.culture.ru/api/rubrics?sort=level&limit='.$limit_rubrics.'&page=' . $page_rubrics, true))->pagination->total;
        $rubrics_download = [];

        $page_events = 1;
        $total_events =  1000;

        $total = 10;
        $rubArr = 0;

        echo 'Download start    ';
        echo 'Download step 1    ';
         while ($total_rubrics >= 0) {
            $rubrics = json_decode(file_get_contents('https://www.culture.ru/api/rubrics?sort=level&limit='.$limit_rubrics.'&page=' . $page_rubrics, true));
            foreach ($rubrics->items as $rubric) {
                if (!$rubric->parentId){
                    if(!SightType::where('cult_id', $rubric->_id)->first()) {
                        $types = [
                            'name' => $rubric->title,
                            'ico' => 'none',
                            'cult_id' => $rubric->_id,
                        ];
                        SightType::create($types);
                    }
                } else {
                    //$cult = SightType::where('cult_id', $rubric->parentId)->first();
                    if (SightType::where('cult_id', $rubric->parentId)->first()) {
                        if(!SightType::where('cult_id', $rubric->_id)->first()) {
                            $types = [
                                'name' => $rubric->title,
                                'ico' => 'none',
                                'cult_id' => $rubric->_id,
                                'stype_id' => SightType::where('cult_id', $rubric->parentId)->firstOrFail()->id,
                            ];
                            SightType::create($types);
                        }
                    } else {
                        $rubrics_download[] = $rubric;
                    }
                }
            }
            $total_rubrics = $total_rubrics - 1;
            $page_rubrics = $page_rubrics + 1;
        }
        echo 'Download step 2    ';
        //print_r($rubrics_download);
        function retrySearch($rubrics) {     
            foreach($rubrics as $rubric) {
                if (SightType::where('cult_id', $rubric->parentId)->first()) { 
                    $types = [
                        'name' => $rubric->title,
                        'ico' => 'none',
                        'cult_id' => $rubric->_id,
                        'stype_id' => SightType::where('cult_id', $rubric->parentId)->firstOrFail()->id,
                    ];
                    SightType::create($types);
                } else {
                    $rubrics_retry[] = $rubric;
                    $rubric_parent = json_decode(file_get_contents('https://www.culture.ru/api/rubrics/' . $rubric->parentId, true));
                    if (!$rubric_parent->parentId) {
                        $types = [
                            'name' => $rubric_parent->title,
                            'ico' => 'none',
                            'cult_id' => $rubric_parent->_id,
                            'stype_id' => SightType::where('cult_id', $rubric_parent->parentId)->firstOrFail()->id,
                        ];
                    SightType::create($types);
                    } else {
                        $types = [
                            'name' => $rubric_parent->title,
                            'ico' => 'none',
                            'cult_id' => $rubric_parent->_id,
                        ];
                    SightType::create($types);
                    }
                }
            }
             if (!empty($rubrics_retry)) {
                retrySearch($rubrics_retry);
            } else {
                print('ok');
            }
        }
        retrySearch($rubrics_download);


        while ($total >= 0) {
            $sights = json_decode(file_get_contents('https://www.culture.ru/api/institutes?page='.$page_institutes.'&limit='.$limit_institutes, true));
            foreach ($sights->items as $sight) {
                if (!Sight::where('cult_id', $sight->_id)->first()) {
                    $type = FileType::where('name', 'image')->get();
                    $sightOut = [
                        'name'          => $sight->title,
                        'sponsor'       => $sight->passport->organization,
                        'city'          => $sight->city->title,
                        'address'       => $sight->address->title,
                        'latitude'      => $sight->coordinates[1],
                        'longitude'     => $sight->coordinates[0],
                        'description'   => $sight->text,
                        'user_id'       => 1,
                        'cult_id'       => $sight->_id,
                        'work_time'       => $sight->workTime,
                    ];

                    $sight->files()->create([
                        "name" => uniqid('image_'),
                        //"link" => $url,
                    ])->file_types()->sync($type[0]->id);
                }
            }
            $total = $total - 1;
            $page_institutes = $page_institutes + 1;
        }

        return print_r('Download end!');
    }
}
