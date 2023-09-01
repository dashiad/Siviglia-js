(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "s1": {
          "TYPE": "String",
          "BLANK_IS_NULL": true
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.s1 = "";
  var status = t1["*s1"].__hasOwnValue() === false && t1["*s1"].__isEmptyValue("") === true;
  t1.s1 = "hola";
  status = t1["*s1"].__hasOwnValue() === true && t1["*s1"].__isEmptyValue("hola") === false;
  t1.destruct();
  return status

//codeEnd
//callbackEnd
})