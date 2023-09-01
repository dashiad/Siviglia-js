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
  var thrown = false;
  var result = true;
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  try {
    cnt.setValue({"one": "a", "two": "lalas"});
  } catch (e) {
    result = (result && e.type == "StringException" && e.code == Siviglia.types.StringException.ERR_TOO_SHORT);
    thrown = true;
  }
  cnt.destruct();
  return result && thrown;

//codeEnd
//callbackEnd
})