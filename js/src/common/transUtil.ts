export function transStr2Str(str:Array<String>|String):String{
    if(typeof str ==="string"){
        return str;
    }else if(Array.isArray(str)){
        return str.join("");
    }else{
        return str.toString();
    }
}