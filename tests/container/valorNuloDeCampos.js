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
          "REQUIRED": true
        },
        "three": {
          "TYPE": "Integer"
        },
        "four": {
          "TYPE": "Integer",
          "KEEP_KEY_ON_EMPTY": true
        }
      }
    }
//definitionEnd

//codeInit
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  cnt.setValue({"one": "tres", "two": "lalas"});
  var tmp = cnt.getValue();
  var status = tmp.three === null && tmp.four === null;
  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})