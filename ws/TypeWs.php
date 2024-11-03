<?php
require_once __DIR__ . '/../service/TypeService.php';
require_once __DIR__ . '/../bean/Type.php';
class TypeWs
{
  private TypeService $service;
  public function __construct()
  {
      $this->service=new TypeService();
  }
  public function findById($id)
  {
      $type=$this->service->findById($id);
      if($type){
          $response=['status'=>200,'data'=>$type->toArray()];
      }else{
          $response=['status'=>204,'message'=>'Type Not found'];
      }
      return json_encode($response);
  }
    public function findAll() {
        $types = $this->service->findAll();
        $responses = [];
        foreach ($types as $type) {
            $responses[] = $type->toArray();
        }
        if (!empty($responses)) {
            return json_encode(["status" => 200, "data" => $responses]);
        } else {
            return json_encode(['status' => 204, 'message' => 'No types found']);
        }
    }

    public  function save($type)
  {
      $res=$this->service->save($type);
      if($res==1){
          $response=['status'=>201,'message'=>'saved of type successful'];
      }else {
          $response=['status'=>204,'message'=>'saved of type no successful'];
      }
      return json_encode($response);
  }
  public  function update($type)
  {
      $res=$this->service->update($type);
      if($res==1){
          $response=['status'=>201,'message'=>'update  type successful'];
      }elseif ($res==-1){
          $response=['status'=>204,'message'=>'update  type no successful'];
      }else{
          $response=['status'=>204,'message'=>'Type not found '];
      }
      return json_encode($response);
  }
  public function addType($type)
  {
      $res=$this->service->addType($type);
      if($res==1){
          $response=['status'=>201,'message'=>'add new type is successful'];

      }elseif($res==-1){
          $response=['status'=>204,'message'=>'add type is not successful'];
      }else{
          $response=['status'=>400,'message'=>'This type is already exist'];
      }
      return json_encode($response);
  }
  public function deleteById($id)
  {
      $res=$this->service->deleteById($id);
      if($res==1){
          $response=['status'=>200,'message'=>'type is delete'];
      }elseif($res==-1){
          $response=['status'=>204,'message'=>'The type is not delete'];
      }else{
          $response=['status'=>404,'message'=>'The type not found'];
      }
      return json_encode($response);
  }
}