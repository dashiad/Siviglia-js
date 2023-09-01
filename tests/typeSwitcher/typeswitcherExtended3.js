(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "TypeSwitcher",
          "ON": [
            {
              "IS": "Array",
              "THEN": "TYPE1"
            },
            {
              "IS": "String",
              "THEN": "TYPE2"
            }
          ],
          "ALLOWED_TYPES": {
            "TYPE1": {
              "TYPE": "Array",
              "ELEMENTS": {
                "TYPE": "String"
              }
            },
            "TYPE2": {
              "TYPE": "String"
            }
          }
        }
      }
    }
//definitionEnd

//codeInit

  var t1 = new Siviglia.model.BaseTypedObject(def);

  t1.setValue({"f1": "Hola?"});
  var st1 = (JSON.stringify(t1.getPlainValue()) == '{"f1":"Hola?"}');

  t1.setValue({"f1": ["a", "b", "c"]});
  st1 = st1 && JSON.stringify(t1["*f1"].subNode.__definition) == '{"TYPE":"Array","ELEMENTS":{"TYPE":"String"}}';
  t1.destruct();
  st1 = st1 && countListeners() == 0;
  return st1;

//codeEnd
//callbackEnd
})