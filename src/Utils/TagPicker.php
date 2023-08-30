<?php
namespace Xypp\PayToRead\Utils;
class TagPicker{
    public static function TagPicker(string $content,bool $updateId = true){
        $inCodeBlock = false;
        $queue = array();
        $closedTag = array();
        $result = "";
        for($i=0;$i<strlen($content);$i++){
            if(!$inCodeBlock && substr($content,$i,4) == "[pay"){
                $start = $i;
                $end = strpos($content,"]",$i);
                if($end === false){
                    break;
                }
                $p = substr($content,$i+1,$end-$i-1);
                $parts = explode(" ",$p);
                $params = array();
                foreach($parts as $p){
                    $args = explode("=",$p);
                    if(count($args)!=2)continue;
                    $arg = trim($args[0],"\"'` \t\r\n");
                    $val = trim($args[1],"\"'` \t\r\n");
                    $params[$arg] = $val;
                }
                array_push($queue,array("start_tag"=>array($start,$end),"end_tag"=>null,"params"=>$params));
                $i = $end;
            }else if(!$inCodeBlock && substr($content,$i,6) == "[/pay]"){
                $top = array_pop($queue);
                $top['end_tag'] = array($i,$i+5);
                $top['params']['depth']=count($queue);
                $top['params']['new']=false;
                array_push($closedTag,$top);
                $i = $i + 5;
            }else if(substr($content,$i,3) == '```'){
                $i = $i + 2;
                $inCodeBlock = !$inCodeBlock;
            }
        }
        if($updateId){
            $idmx = 0;
            for($i = count($closedTag) - 1;$i >= 0;$i--){
                $tg = $closedTag[$i];
                if(!isset($tg['params']['id'])){
                    $id = $idmx++;
                    $closedTag[$i]['params']['new']=true;
                    $closedTag[$i]['params']['id']=$id;
                    $content_p2 = str_split($content,$tg['start_tag'][1]);
                    $content = $content_p2[0]." id=[newId]#".$id."#[/newId]".$content_p2[1];
                }
            }
        }
        return [$closedTag,$content];
    }
    public static function TagPickerHTML(string $content,bool $updateId = true){
        $inCodeBlock = false;
        $queue = array();
        $closedTag = array();
        for($i=0;$i<strlen($content);$i++){
            if(!$inCodeBlock && substr($content,$i,12) == "<pay-to-read"){
                $start = $i;
                $end = strpos($content,">",$i);
                if($end === false){
                    break;
                }
                $p = substr($content,$i+1,$end-$i-1);
                $parts = explode(" ",$p);
                $params = array();
                foreach($parts as $p){
                    $args = explode("=",$p);
                    if(count($args)!=2)continue;
                    $arg = trim($args[0],"\"'` \t\r\n\0\x0B");
                    if(substr($arg,0,5)=="data-"){
                        $arg = substr($arg,5);
                    }
                    $val = trim($args[1],"\"'` \t\r\n\0\x0B");
                    $params[$arg] = $val;
                }
                array_push($queue,array("start_tag"=>array($start,$end),"end_tag"=>null,"params"=>$params));
                $i = $end;
            }else if(!$inCodeBlock && substr($content,$i,14) == "</pay-to-read>"){
                $top = array_pop($queue);
                $top['end_tag'] = array($i,$i+13);
                $top['params']['depth']=count($queue);
                $top['params']['new']=false;
                array_push($closedTag,$top);
                $i = $i + 5;
            }else if(substr($content,$i,4) == '<pre'){
                $cnxt = substr($content,$i+4,1);
                if($cnxt == ">" || $cnxt == " " || $cnxt == "\t"){
                    $i = $i + 5;
                    $inCodeBlock = true;
                }
            }else if(substr($content,$i,6) == '</pre>'){
                $i = $i + 5;
                $inCodeBlock = false;
            }
        }
        if($updateId){
            // 补齐ID：新建项目的ID创建
            $idmx = 0;
            for($i = count($closedTag) - 1;$i >= 0;$i--){
                $tg = $closedTag[$i];
                if(!isset($tg['params']['id'])){
                    $id = ($idmx++);
                    $closedTag[$i]['params']['new']=true;
                    $closedTag[$i]['params']['id']=$id;
                    $content_p2 = str_split($content,$tg['start_tag'][1]);
                    $content = $content_p2[0]." id=".$id.$content_p2[1];
                }
            }
        }
        return [$closedTag,$content];
    }

}
