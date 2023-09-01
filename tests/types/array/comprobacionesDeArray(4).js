(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String"
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );

  var nErrors = 0;
  var nChanges = 0;

  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });

  t1.f3 = ["aa", "bb"];
  // Generamos una copia eventizada, para escuchar cambios.
  var h = {};
  h.q = t1["*f3"].getValue();
  Siviglia.Path.eventize(h, "q");
  var nRefChanges = 0;
  h["*q"].addListener("CHANGE", function () {
    nRefChanges++;
  });

  // Mientras siga siendo el mismo objeto, se deben seguir recibiendo eventos:
  t1.f3.push("cc");
  var status = (nRefChanges == 1 && nChanges == 2);

  // Ahora se cambia el valor completamente.Esto no deberia generar eventos en h.q
  t1.f3 = ["dd", "ee", "ff"];
  status = status && (nRefChanges == 1 && nChanges == 3);

  // Scomprobacion de path.
  var fPath = t1.f3["*0"].__getFieldPath();

  status = status && t1.f3["*0"].__getFieldPath() == "/f3/0";


  t1.destruct();
  h.__destroy__();


  status = status && countListeners() == 0;

  // Se limpian manualmente los listeners.
  Siviglia.Dom.existingListeners = [];


  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})