(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "d1": {
          "TYPE": "Dictionary",
          "BLANK_IS_NULL": true,
          "VALUETYPE": {
            "TYPE": "String",
            "BLANK_IS_NULL": true
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.d1 = {};
  var status = t1["*d1"].__hasOwnValue() === false && t1["*d1"].__isEmptyValue({}) === true;
  t1.d1 = {a: ""};
  status = status && t1["*d1"].__hasOwnValue() === false && t1["*d1"].__isEmptyValue({a: ""}) === true;
  t1.d1 = {a: "hola"};
  status = status && t1["*d1"].__hasOwnValue() === true && t1["*d1"].__isEmptyValue({a: "hola"}) === false;
  t1.destruct();
  return status;


//codeEnd
//callbackEnd
})