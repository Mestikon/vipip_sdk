<?php
/**
 * SocialTariff class file
 */

namespace Vipip\Service\Settings;
use Vipip\Message;
use Vipip\Service\Service;
use Vipip\Vipip;

/**
 * Class SocialTariff
 * @package Vipip\Service\Settings
 */
class SocialTariff extends Tariff{

    /**
     * @var string name of module
     */
    protected $vipip_moule = 'social';

    const SEX_ANY = '';
    const SEX_MALE = 'm';
    const SEX_FEMALE = 'f';

    /**
     * SocialTariff constructor.
     * @param Service $service
     */
    public function __construct(Service $service = null){
        $this->extendAttributes([
            'age_min' => 0,
            'age_max' => 0,
            'friends_id' => [
                'value' => 0,
                'readOnly' => true
            ],
            'sex' => self::SEX_ANY,
            'url' =>'',
            'message' => '',
            'answerid' => ''
        ]);

        parent::__construct($service);
    }

    /**
     * Initialization of the data service class
     * @param Service $service
     */
    public function serviceInit(Service $service){
        $this->setTypeId($service->typeid);

        $this->setAttributes([
            'url' => $service->url,
            'age_min' => $service->age_min,
            'age_max' => $service->age_max,
            'sex' => $service->sex,
            'friends_id' => $service->friends_id,
            'message' => $this->extractParam($service->params, 'message'),
            'answerid' => $this->extractParam($service->params, 'answer_id')
        ]);
    }

    /**
     * Extracting params by name from params if exists
     * @param $params
     * @param $name
     * @return string
     */
    private function extractParam($params, $name){
        if( $params = json_decode($params,1) ){
            return isset($params[$name]) ? $params[$name] : '';
        }
        return '';
    }

    /**
     * List the attributes required to fulfill the request
     * @return array
     */
    public function getRequestAttributes(){
        return [
            'typeid' => $this->id,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'friends_id' => $this->friends_id,
            'sex' => $this->sex,
            'url' => $this->url,
            'message' => $this->message,
            'answerid' => $this->answerid
        ];
    }

    /**
     * Setting friends
     * @param $id
     * @throws \Exception
     */
    public function setFriends_Id($id){
        $friendsList = Vipip::module($this->vipip_moule)->getFriendsList();

        $List = array_filter($friendsList, function($friend) use ($id) {
            return $friend['friends'] == $id;
        });

        if( empty($List) )
            throw new \Exception(Message::t("The value of the parameter '{parameter}'={value} is incorrect", ['{parameter}'=>'friends_id', '{value}'=>$id]));

        $this->setAttributes(['friends_id'=>$id]);
    }
}