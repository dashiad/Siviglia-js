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
  var cnt = Siviglia.types.TypeFactory.getType({"fieldName": "a", "fieldPath": "/"}, def, null, null);
  var thrown = false;
  var result = true;
  try {
    cnt.setValue({one: "tres"});
    cnt.save();
  } catch (e) {
    result = result && e.type === "BaseTypedException" && e.code === Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD;
    // Tiene que estar errored.
    thrown = true;
  }
  cnt.destruct();
  return result && thrown;

//codeEnd
//callbackEnd
})