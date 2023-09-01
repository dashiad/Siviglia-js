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
          "MAXLENGTH": 10,
          "KEEP_KEY_ON_EMPTY": true
        },
        "two": {
          "TYPE": "String"
        }
      }
    }
//definitionEnd

//codeInit
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  cnt.setValue({"one": null, two: null});
  var plain = cnt.getPlainValue();
  cnt.destruct();
  return null === plain.one;

//codeEnd
//callbackEnd
})