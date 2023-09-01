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
  var result = true;
  val = obj.getPath("/one");
  result = result && "str_one" == val;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})