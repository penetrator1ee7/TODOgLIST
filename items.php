<?php
$data=json_decode(file_get_contents("table.json"));
header('Content-Type: application/json');
//echo $_SERVER['REQUEST_URI'];
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST'){
    $stream=json_decode(file_get_contents("php://input"));

    $maxId=0;
    if(isset($data[1])){
    for($i=0;$i<count($data);$i++){
    if($maxId<$data[$i]->id){
        $maxId = $data[$i]->id;
    }
  }
}elseif(isset($data[0])){$maxId=$data[0]->id;}else{$maxId=1;};
    $maxId++;
    if(gettype($stream->itemName)=='string') {
        $data[] = array('id' => $maxId, 'itemName' => $stream->itemName);
        file_put_contents('table.json', json_encode($data));

        header("HTTP/1.0 201 CREATED");
    }else{
        header("HTTP/1.0 400 BAD REQUEST");
    }
}elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='DELETE'){
    $delFlag=false;
    if(isset($_SERVER['REQUEST_URI'])){
        $ExReq=explode('/',$_SERVER['REQUEST_URI']);
        $delId=$ExReq[count($ExReq)-1];
        for($i=0;$i<count($data);$i++){
            if($data[$i]->id==$delId){
                array_splice($data, $i, 1);
                $delFlag=true;
            }
        }
    }
if($delFlag==true){
    file_put_contents('table.json', json_encode($data));
    header("HTTP/1.0 200 OK");
}else{
    header("HTTP/1.0 404 Not Found");
}
}elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='PUT'){
    $stream=json_decode(file_get_contents("php://input"));
    if(gettype($stream->id)=='integer' && gettype($stream->itemName)=='string'){

        for($i=0;$i<count($data);$i++){
            if($data[$i]->id==$stream->id){
                $data[$i]->itemName=$stream->itemName;
            }
        }

        file_put_contents('table.json', json_encode($data));
        header("HTTP/1.0 200 OK");
    }else{
        header("HTTP/1.0 400 BAD REQUEST");
    }
}
echo json_encode($data);
/*$_SERVER['REQUEST_URI']='items.php/1';
$delFlag=false;
if(isset($_SERVER['REQUEST_URI'])){
    $ExReq=explode('/',$_SERVER['REQUEST_URI']);
    $delId=$ExReq[count($ExReq)-1];
//$delId=(int)$delId;
    for($i=0;$i<count($data);$i++){
        if($data[$i]->id==$delId){
            unset($data[$i]);
            echo 'found';
            $delFlag=true;
        }
    }
}
if($delFlag==true){
    echo 'ok';
}else{
    echo 'no';
}*/
