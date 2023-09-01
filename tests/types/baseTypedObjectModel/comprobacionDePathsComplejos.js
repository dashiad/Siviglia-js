(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "one": {
          "TYPE": "String"
        },
        "arr": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String"
          }
        },
        "dict": {
          "TYPE": "Container",
          "FIELDS": {
            "c1": {
              "TYPE": "String"
            },
            "c2": {
              "TYPE": "Array",
              "ELEMENTS": {
                "TYPE": "String"
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var obj = new Test.SimpleTypedObject(def);
  obj.one = "str_one";
  obj.arr = ["c1", "c2"];
  obj.dict = {}
  obj.dict.c1 = "f1";
  obj.dict.c2 = ["first", "second"];
  var result = true;
  val = obj.getPath("/arr/0");
  result = result && "c1" == val;
  val2 = obj.getPath("/dict/c1");
  result = result && "f1" == val2;
  val3 = obj.getPath("/dict/c2/1");
  result = result && "second" == val3;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})