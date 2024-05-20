require 'jwt'

key_file = 'AuthKey_SAK8RGL4HR.p8'
team_id = 'L3GYA8KNK5'
client_id = 'mlcity.ru.auth'
key_id = 'SAK8RGL4HR'

ecdsa_key = OpenSSL::PKey::EC.new IO.read key_file

headers = {
  'kid' => key_id
}

claims = {
	'iss' => team_id,
	'iat' => Time.now.to_i,
	'exp' => Time.now.to_i + 86400*180,
	'aud' => 'https://appleid.apple.com',
	'sub' => client_id,
}

token = JWT.encode claims, ecdsa_key, 'ES256', headers

puts token