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
  obj.one = "arr";
  obj.arr = ["c1", "c2"];
  var val = obj.getPath("/{%/one%}/0");
  var result = true;
  result = result && "c1" == val;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})