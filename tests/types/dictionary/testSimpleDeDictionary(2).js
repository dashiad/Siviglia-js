(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Dictionary",
          "SET_ON_EMPTY": true,
          "VALUETYPE": {
            "TYPE": "Container",
            "FIELDS": {
              "sf3": {
                "TYPE": "String",
                "MINLENGTH": 2
              }
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

  t1.f3 = {uno: {sf3: "a1"}, dos: {sf3: "a2"}};

  var h = {};
  h.q = t1["*f3"].getValue();
  Siviglia.Path.eventize(h, "q");
  var nRefChanges = 0;
  h["*q"].addListener("CHANGE", function () {
    nRefChanges++;
  });
  // Mientras siga siendo el mismo objeto, se deben seguir recibiendo eventos:
  t1.f3.tres = {sf3: "cc"};
  var status = (nRefChanges == 1 && nChanges == 2);

  // Ahora se cambia el valor completamente.Esto no deberia generar eventos en h.q
  t1.f3 = {"cuatro": {sf3: "dd"}, "cinco": {sf3: "ee"}};
  status = status && (nRefChanges == 1 && nChanges == 3);

  delete t1.f3.cuatro;
  var q = t1.f3.cuatro;
  var ss = t1.f3["[[KEYS]]"];
  status = status && ss.length == 1 && (ss[0].LABEL == "cinco") &&
    (typeof t1.f3.cuatro === "undefined") &&
    (typeof t1.f3["*cuatro"] === "undefined");


  t1.destruct();


  // Sin embargo, hay que tener en cuenta que el listener de h.q es ahora "anonimo".
  // h.q no tiene un destruct (era un objeto plano), pero mantiene una referencia a un EventListener,
  // por lo que aun debe haber 1 listener activo.
  status = status && countListeners() == 1;
  h.__destroy__();
  // Se limpian manualmente los listeners.
  Siviglia.Dom.existingListeners = [];
  return status;

//codeEnd
//callbackEnd
})