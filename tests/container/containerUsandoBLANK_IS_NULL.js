(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "BLANK_IS_NULL": true,
      "FIELDS": {
        "F1": {
          "TYPE": "String",
          "BLANK_IS_NULL": true
        }
      }
    }
//definitionEnd

//codeInit
  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  cnt.setValue({});
  var status = cnt.__hasOwnValue() === false && cnt.__isEmptyValue({}) === true;
  cnt.setValue({F1: ""});
  status = status && cnt.__hasOwnValue() === false && cnt.__isEmptyValue({F1: ""}) === true;
  status = status && cnt.getValue() === null;
  cnt.setValue({F1: "aaa"});
  status = status && cnt.__hasOwnValue() === true && cnt.__isEmptyValue({F1: "aaa"}) === false;

  cnt.destruct();
  return status;

//codeEnd
//callbackEnd
})