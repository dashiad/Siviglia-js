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
              "FIELD": "ft",
              "IS": "Array",
              "THEN": "TYPE1"
            },
            {
              "FIELD": "aa",
              "IS": "Present",
              "THEN": "TYPE2"
            }
          ],
          "ALLOWED_TYPES": {
            "TYPE1": {
              "TYPE": "Container",
              "FIELDS": {
                "ft": {
                  "TYPE": "Array",
                  "ELEMENTS": {
                    "TYPE": "String"
                  }
                },
                "f2": {
                  "TYPE": "Boolean"
                }
              }
            },
            "TYPE2": {
              "TYPE": "Container",
              "FIELDS": {
                "aa": {
                  "TYPE": "Integer"
                },
                "f2": {
                  "TYPE": "String"
                }
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.setValue({"f1": {ft: ["a", "b", "c"], "f2": true}});
  var st1 = (JSON.stringify(t1.getPlainValue()) == '{"f1":{"ft":["a","b","c"],"f2":true}}');
  t1.f1 = {aa: 1};
  st1 = st1 && JSON.stringify(t1["*f1"].subNode.__definition) == '{"TYPE":"Container","FIELDS":{"aa":{"TYPE":"Integer"},"f2":{"TYPE":"String"}}}';
  t1.destruct();
  return st1;

//codeEnd
//callbackEnd
})