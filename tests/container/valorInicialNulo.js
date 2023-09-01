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
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  var n = cnt.getValue();
  cnt.destruct();
  return n === null;

//codeEnd
//callbackEnd
})