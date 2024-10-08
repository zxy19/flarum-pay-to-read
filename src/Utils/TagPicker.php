<?php
namespace Xypp\PayToRead\Utils;

class TagPicker
{
    public static function TagPicker(string $content, bool $updateId = true)
    {
        $inCodeBlock = false;
        $queue = array();
        $closedTag = array();
        $startPos = array();
        $result = "";
        for ($i = 0; $i < strlen($content); $i++) {
            if (!$inCodeBlock && substr($content, $i, 4) == "[pay") {
                $start = $i;
                $end = strpos($content, "]", $i);
                if ($end === false) {
                    break;
                }
                $p = substr($content, $i + 1, $end - $i - 1);
                $parts = explode(" ", $p);
                $params = array();
                foreach ($parts as $p) {
                    $args = explode("=", $p);
                    if (count($args) != 2)
                        continue;
                    $arg = trim($args[0], "\"'` \t\r\n");
                    $val = trim($args[1], "\"'` \t\r\n");
                    //Fix typo
                    if ($arg == "ammount")
                        $arg = "amount";
                    $params[$arg] = $val;
                }
                array_push($queue, array("start_tag" => array($start, $end), "end_tag" => null, "params" => $params));
                $i = $end;
            } else if (!$inCodeBlock && substr($content, $i, 6) == "[/pay]") {
                if (!count($queue))
                    continue;
                $top = array_pop($queue);
                $top['end_tag'] = array($i, $i + 5);
                $top['params']['depth'] = count($queue);
                $top['params']['new'] = false;
                $idx = array_push($closedTag, $top) - 1;
                $startPos[$idx] = $top['start_tag'][0];
                $i = $i + 5;
            } else if (substr($content, $i, 3) == '```') {
                $i = $i + 2;
                $inCodeBlock = !$inCodeBlock;
            }
        }
        if ($updateId) {
            $idmx = 0;
            arsort($startPos);
            foreach ($startPos as $i => $_) {
                $tg = $closedTag[$i];
                if (!isset($tg['params']['id']) || $tg['params']['id'] == 0) {
                    $id = $idmx++;
                    $closedTag[$i]['params']['new'] = true;
                    $closedTag[$i]['params']['id'] = $id;
                    $content = substr_replace($content, " id=[newId]#" . $id . "#[/newId]", $tg['start_tag'][1], 0);
                }
            }
        }
        $content = str_ireplace("id=0", "", $content);
        return [$closedTag, $content];
    }
}
