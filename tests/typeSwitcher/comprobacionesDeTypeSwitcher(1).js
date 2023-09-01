(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "CONTENT_FIELD": "value",
          "ALLOWED_TYPES": {
            "TIPO3": {
              "TYPE": "Container",
              "FIELDS": {
                "sf3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO4": {
              "TYPE": "Container",
              "FIELDS": {
                "sf1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "sf2": {
                  "TYPE": "Integer"
                }
              }
            }
          },
          "DEFAULT": {
            "TYPE": "TIPO3",
            "value": {
              "sf3": "aaa",
              "sf4": 10
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  var nChanges = 0;
  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });
  // Comprobamos que, en este punto, ya que t1.f3.TYPE=="TIPO3" (por el valor por defecto),
  // existen sf3 y sf4:
  var initStatus = (t1.f3.value.sf3 === "aaa" && nChanges == 0);
  var thrown = false;
  try {
    var v1 = t1.f3.value.sf2;
  } catch (e) {
    thrown = true;
  }
  initStatus = initStatus && thrown;
  // Se cambia ahora el tipo del typeswitcher.Tienen que haber desaparecido los tipos anteriores,
  // y aparecer los nuevos campos, aunque esten a nulo.
  t1.f3.TYPE = "TIPO4";
  t1.f3.value = {};
  thrown = false;
  var secondStatus = (t1.f3.value.sf1 == null && nChanges == 1);
  try {
    v1 = t1.f3.value.sf3;
  } catch (e) {
    thrown = true;
  }
  secondStatus = secondStatus && thrown;
  // Se deshace el cambio.
  t1.f3.TYPE = "TIPO3";
  t1.f3.value = {};

  var thirdStatus = (t1.f3.value.sf3 == null && nChanges == 2);
  thrown = false;
  try {
    v1 = t1.f3.value.sf1;
  } catch (e) {
    thrown = true;
  }
  thirdStatus = thrown && thirdStatus;
  t1.destruct();
  return initStatus && secondStatus && thirdStatus && countListeners() === 0;

//codeEnd
//callbackEnd
})