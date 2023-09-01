(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "BLANK_IS_NULL": true,
          "ELEMENTS": {
            "TYPE": "String"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );

  t1.f3 = [];
  var status = t1["*f3"].__hasOwnValue() === false && t1["*f3"].__isEmptyValue([]);
  t1["*f3"].setValue(null);
  status = status && t1["*f3"].__hasOwnValue() === false;
  t1.f3 = ["aa"];
  status = status && t1["*f3"].__hasOwnValue() !== false;
  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})