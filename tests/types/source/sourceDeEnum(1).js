(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Enum",
          "VALUES": [
            "Value1",
            "Value2",
            "Value3"
          ],
          "DEFAULT": "Value3"
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  var source = t1["*f3"].__getSource();
  var loaded = 0;
  var changed = 0;
  var data = null;
  source.addListener("EVENT_LOADED", null, function () {
    loaded = 1;
  });
  source.addListener("CHANGE", null, function (ev, params) {
    changed = 1;
    console.dir(params);
    data = params.value
  });
  source.fetch();
  t1.destruct();
  return loaded === 1 && changed === 1 && data.length === 3 && data[2]["LABEL"] === "Value3" && data[2]["VALUE"] === 2 && countListeners() === 0;

//codeEnd
//callbackEnd
})