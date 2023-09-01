(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "FIELDS": {
        "one": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 10
        },
        "two": {
          "TYPE": "String",
          "DEFAULT": "Hola"
        }
      }
    }
//definitionEnd

//codeInit
  var cnt = Siviglia.types.TypeFactory.getType({"fieldName": "a", "path": "/"}, def, null, null);
  var p = cnt.__getFieldPath();
  var status = (p === "/a");
  cnt.setValue({});
  status = status && "/a/one" === cnt["*one"].__getFieldPath();
  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})