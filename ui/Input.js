Siviglia.Utils.buildClass({
  context: 'Siviglia.widgets.jqwidgets',
  classes: {
    Loading: {
      inherits: "Siviglia.UI.Expando.View",
      methods: {}
    },
    RecursiveDSList: {
      inherits: 'Siviglia.UI.Expando.View',
      methods: {
        preInitialize: function (params) {
          this.itemList = []
          this.model = params.innerListParams.model
          this.dataSource = params.innerListParams.dataSource
          this.keys = params.innerListParams.keys ? params.innerListParams.keys : {}
          this.label = params.innerListParams.label
          this.value = params.innerListParams.value
          this.listParam = params.innerListParams.listParam
          this.keysParam = params.innerListParams.keysParam
          this.innerListParams = params.innerListParams.innerListParams ? params.innerListParams.innerListParams : {}
          this.innerListReference = ''

          if (this.model && this.dataSource) {
            this.ds = new Siviglia.Model.DataSource(this.model, this.dataSource, this.keys)
            return this.ds.unfreeze().then(function () {
              this.itemList = this.ds.data
            }.bind(this))
          }
        },
        onClickedItem: function (node, params) {
          if (this.listParam === 'keys')
            this.innerListParams.keys[this.keysParam] = params.clickedValue
          else
            this.innerListParams[this.listParam] = params.clickedValue

          this.__parentView.listValue = params.clickedValue
          if (this.__parentView.__parentView !== null)
            this.__parentView.__parentView.listValue = this.__parentView.listValue

          this.innerListReference = params.clickedLabel
        },
      }
    }
  }
});
Siviglia.Utils.buildClass(
  {
    context: 'Siviglia.inputs.jqwidgets',
    classes: {
      Factory: {
        methods: {
          getInput: function (type, params, form) {
            var stype = type.__definition["TYPE"];
            var d = $.Deferred();
            if (!Siviglia.isset(params))
              params = {};
            //if(Siviglia.isset(params.controller))
            //    type.setController(params.controller);
            var dv = $('<div></div>');
            var p = params || {};
            if (typeof form !== "undefined") {
              var formParams = form.getFormParams(type.__getFieldPath());
              if (formParams) {
                for (var k in formParams)
                  p[k] = formParams[k];
              }
            }
            p.type = type;
            p.form = form;


            var input = Siviglia.issetOr(p["INPUT"], null);
            if (!input) {
              switch (stype) {
                case "Integer":
                case "String": {
                  if (type.__hasSource())
                    input = "ComboBox";
                  else
                    input = stype;
                }
                  break;
                default: {
                  input = stype
                }
              }
            }
            if (type.relaxed) {
              switch (stype) {
                case "Boolean": {
                  input = "BooleanCombo";
                }
                  break;
              }
            }
            var stack = new Siviglia.Path.ContextStack();
            var instance = new Siviglia.inputs.jqwidgets[input](
              //"Siviglia.inputs.jqwidgets."+input,
              input.indexOf(".") > -1 ? input : "Siviglia.inputs.jqwidgets." + input,
              p,
              {},
              dv,
              stack
            );
            instance.__build().then(function () {
              // Se crea el layout y se le pasa la instancia.

              d.resolve(instance);

            })
            return d;

          }
        }
      },


      BaseInput: {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
          //this.clearErrors();
          // Destruccion del jqxInput
          var widgetName = this.getJqxWidgetName();

          if (this.inputNode) {
            this.inputNode[widgetName]('destroy');
            var ariaNode = this.inputNode.attr("aria-owns");
            if (ariaNode) {
              var R = new RegExp(ariaNode, "i");
              var curEvents = $._data(document, "events");
              for (var k in curEvents) {
                var curList = curEvents[k];
                for (var j = 0; j < curList.length; j++) {
                  if (R.exec(curList[j].namespace)) {
                    jQuery(document).off(curList[j]["type"], curList[j]["namespace*"]);
                  }
                }
              }
            }
          }
          if (this.stateDriven) {
            var controller = this.type.__getController();
            var stDef = controller.getStateDef();
            var stateType = stDef.getStateType();
            stateType.removeListeners(this);
          }
          if (this.type)
            this.type.removeListeners(this);
          for (var k in this.subWidgets) {
            this.subWidgets[k].destruct();
          }
          // Si alguien nos ha "proxificado", se elimina el event manager
          if (typeof this.__isProxy__ !== "undefined") {
            if (typeof this.__destruct__ !== "undefined")
              this.__destruct__();
            else {
              if (typeof this.__ev__ !== "undefined")
                this.__ev__.destruct();
            }
          }
        },
        methods: {
          preInitialize: function (params) {
            this.subWidgets = {};
            this.errored = false;
            this.controller = this.parentView;
            this.stateDriven = false;
            this.tooltipNode = null;
            this.changing = false;
            this.hasGlobalErrors = false;
            this.description = "";
            this.errorMessage = "";
            this.form = Siviglia.issetOr(params.form, null);
            //El inputWrapper es la estructura que contiene este
            // input, y que consiste en el input, la label, la ayuda,etc
            this.inputWrapper = Siviglia.issetOr(params.inputWrapper, null);
            this.type = params.type;

            this.type.addListener("CHANGE", this, "onTypeChanged", "BaseInput-TypeChanged");
            // Nota: El Listener de error ya no va a estar aqui en el input, si no en el InputContainer.
            // (getInputFor del método SimpleInputContainer())

            this.helpMessage = Siviglia.issetOr(this.type.__definition.HELP, null);
            this.hasHelpMessage = (this.helpMessage != null ? true : false);
            this.inputParams = Siviglia.issetOr(params.inputParams, {});
            this.formParams = Siviglia.issetOr(params.formParams, {});
          },
          initialize: function (params) {
            var opts = this.getDefaultInputOptions();
            for (var k in this.inputParams)
              opts[k] = this.inputParams[k];

            this.createInput(opts);
            if (!this.type.__isEditable())
              this.disable();
            this.checkState();

            this.inputNode[this.getJqxWidgetName()]('theme', 'light');
            var m = this;

            var changeFunc = this.debounce(
              function () {
                if (m.changing)
                  return;
                m.changing = true;
                try {

                  m.type.setValue(m.getValue());
                  if (m.type.__isErrored()) {
                    var error = m.type.__getError()
                    m.showError(
                      Siviglia.i18n.es.base.getErrorFromJsException(error)
                    );
                  } else
                    m.clearErrors();
                } catch (e) {
                  m.showError(
                    Siviglia.i18n.es.base.getErrorFromJsException(e)
                  );
                }
                m.changing = false;
              }
              , 250);
            // Aqui se escuchan los cambios de input.
            this.inputNode.on("change", changeFunc);
            // Pero si el tipo es un parametro dinamico, necesitamos que se dispare la funcion en "keyup"
            if (this.type.__isDynamic())
              this.inputNode.on("keyup", changeFunc);

            if (this.type.is_set()) {
              var toSet = this.type.getValue();
              this._setInputValue(toSet);

              if (this.type.__isErrored()) {
                var m = this;
                setTimeout(function () {
                  var error = m.type.__getError();

                  m.showError(
                    Siviglia.i18n.es.base.getErrorFromJsException(error)
                  );
                }, 500);
              }
            }
          },
          checkState: function () {
            var controller = this.type.__getController();

            if (controller !== null && controller.getStateDef() !== null) {
              var stDef = controller.getStateDef();
              var stateType = stDef.getStateType();
              if (stateType !== this.type) {
                this.stateDriven = true;
                this.__checkEditable = function (ev) {
                  if (this.type.__isEditable())
                    this.enable();
                  else
                    this.disable();
                }.bind(this);
                stateType.addListener("CHANGE", this, "__checkEditable", "BaseInput-State");
                this.stateDriven = true;
              }
            }
          },
          addSubWidget: function (field, widget) {

            this.subWidgets[field] = widget;
          },
          showPath: function (path) {
            // Aqui no deberia llegar nunca.En los tipos basicos, showPath
            // tendría que ser utilizado en el inputWrapper, que no lo pasaría al input final.
          },
          removeSubWidget: function (field) {
            delete this.subWidgets[field];
          },
          debounce: function (func, wait, immediate) {
            var timeout;
            return function () {
              var context = this, args = arguments;
              var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
              };
              var callNow = immediate && !timeout;
              clearTimeout(timeout);
              timeout = setTimeout(later, wait);
              if (callNow) func.apply(context, args);
            };
          },
          onTypeChanged: function (event, params) {
            if (this.changing === false)
              this._setInputValue(params.data.getValue());
          },
          detach: function () {
            this.Widget$detach();
            this.type.removeListeners(this);
          },
          getDefaultInputOptions: function () {
            return {};
          },
          createInput: function (options) {

            return this.callInp(options)
          },
          _setInputValue: function (toSet) {
            this.changing = true;
            //this.inputNode[this.getJqxWidgetName()]('val', toSet);
            $(this.inputNode).val(toSet);
            this.changing = false;
          },
          getValue: function () {
            //return this.inputNode[this.getJqxWidgetName()]('val');
            return $(this.inputNode).val();
          },

          save: function () {
            try {

              if (typeof this.inputNode !== "undefined" && !this.type.equals(this.getValue()))
                this.type.setValue(this.getValue());
              this.clearErrors();
            } catch (e) {
              this.showError(
                Siviglia.i18n.es.base.getErrorFromJsException(e)
              );
            }
          },

          focus: function () {
            this.rootNode[0].scrollIntoView(true);
            if (this.inputNode)
              this.inputNode.focus();
          },
          showError: function (message, dontAppend) {
            // Se guarda referencia del puntero al nodo del error <span...>
            if (this.inputWrapper !== null)
              this.errorNode = this.inputWrapper.errorNode[0];

            // Nos guardamos si hay excepcion en el tipo
            var exception_input_path = this.type.__errorException;

            // Comprobamos la excepción del tipo. Si es distinto de null, es cuando SI se pinta/muestra el error
            if (exception_input_path !== null) {
              if (typeof this.errorNode !== "undefined") {
                this.errorNode.innerText = message;               // insertamos el mensaje de error en el span del input.
                this.inputNode[0].classList.add("show-errored");  // ponemos clase de error en el input
                this.errorNode.style.display = "";                // hacemos que se vea el <span> eliminando el display: none
              }
            }

            // Añadimos path de error de dicho input
            if (this.form !== null)
              this.form.appendError(this.type.getFullPath(), this);
          },

          clearErrors: function () {
            // Nos guardamos si hay excepcion en el tipo
            var exception_input_path = this.type.__errorException;
            var input_required = this.type.__definition.REQUIRED;

            // Comprobamos que no esté vacío el inputWrapper
            if (this.inputWrapper !== null) {

              // Se guarda referencia del puntero al nodo del error <span...>
              this.errorNode = this.inputWrapper.errorNode[0];

              // comprobacion que el valor de los hijos de rootNode es !== de undefined
              // para poder comprobar que está vacío (y ver si es un campo requerido y vacío, lanzar excepción)
              // Nota: asegurarse que inputNode no es undefined, para cuando se guarda un modelo no de error (https://pastebin.com/eanSrrX5)
              if (typeof this.inputNode !== "undefined") {
                var value_node_empty_required = this.inputNode[0].value;

                if (typeof value_node_empty_required !== "undefined")  //solo si es !== de undefined, asignamos el valor.
                  var node_empty_and_required = value_node_empty_required;
              }


              // si es vacio el campo y está requerido, mostrar error:
              if (typeof this.inputNode !== "undefined" &&
                node_empty_and_required === "" && input_required) {
                // lanzamos una excepcion con el codigo de error de campo requerido (5)
                var e = new Siviglia.model.BaseTypedException(this.type.getFullPath(), Siviglia.model.BaseTypedException.ERR_REQUIRED_FIELD, this.type.__definition);
                this.type.__setErrored(e);
                //throw e;
              } else {

                // Comprobamos la excepción del tipo. Si es distinto de null, es cuando NO se elimina el error
                if (exception_input_path === null) {

                  // Comprobación para evitar dar un error en la consola
                  if (typeof this.inputNode !== "undefined") {
                    // quitamos los estilos de errores del nodo de error y más abajo del input
                    this.errorNode.innerText = "";
                    this.errorNode.style.display = "none";
                    this.inputNode[0].classList.remove("show-errored");  // Se quita la clase que resalta el error del input
                  }

                }// if__exception_input_path

              }
            }

            // Si no está vacío el formulario, se llama a la función que se encarga de eliminar el path del error
            if (this.form !== null)
              this.form.clearError(this.type.getFullPath(), this);
          },
          getJqxWidgetName: function () {
            return "jqxInput";
          },
          callInp: function (obj) {
            return this.inputNode[this.getJqxWidgetName()](obj);
          },
          disable: function () {
            this.callInp({disabled: true});
          },
          enable: function () {
            this.callInp({disabled: false});
          },
          on: function (event, callback) {
            this.inputNode.on(event, callback);
          }
        }
      },
      String: {
        /*
         disabled	Boolean	false
         dropDownWidth	Number/String	null
         displayMember	String	""
         height	Number/String	null
         items	Number	8
         minLength	Number	1
         maxLength	Number	null
         opened	Boolean	false
         placeHolder	String	""
         popupZIndex	Number	20000
         query	String	""
         renderer	function	null
         rtl	Boolean	false
         searchMode	String	'default'
         source	Array, function	[]
         theme	String	''
         valueMember	String	""
         width
         */
        inherits: "Siviglia.inputs.jqwidgets.BaseInput",
        methods: {
          getDefaultInputOptions: function () {
            var d = this.type.__definition;

            if (d.LABEL == null)
              d.LABEL = 'dato';

            // if (d.FIXED !== null)


            var opts = {
              placeHolder: Siviglia.issetOr(this.inputParams["placeholder"], "Insertar " + d.LABEL + "...")
            };
            if (d.MINLENGTH)
              opts.minLength = d.MINLENGTH;
            if (d.MAXLENGTH)
              opts.maxLength = d.MAXLENGTH;
            return opts;

          },
          /*   createInput:function(opts)
             {
                 var d=this.type.__definition;
                 if(typeof d.PARAMTYPE!=="undefined" && d.PARAMTYPE==='DYNAMIC')
                 {
                     // Poner callback de keyup, con debouncer
                 }

             }*/

        }
      },
      Enum: {
        inherits: 'BaseInput',
        methods: {
          getDefaultInputOptions: function () {
            var finalOpts = [];
            var d = this.type.__definition;
            for (var k = 0; k < d.VALUES.length; k++) {
              var c = d.VALUES[k];
              var label, value;
              label = value = c;
              if (typeof c == "object") {
                label = c.LABEL;
                value = c.VALUE;
              }
              finalOpts.push(
                {
                  //   html: '<div tabIndex=0 style="padding:1px">' + label + '</div>',
                  label: label,
                  value: value
                }
              )
            }
            var opts = {
              source: finalOpts, //finalOpts,
              placeHolder: '--Seleccionar...',
              autoComplete: false,
              height: 25,
              displayMember: "label",
              valueMember: "value",
              width: '100%',
              minLength: 0
            };
            return opts;
          },
          getJqxWidgetName: function () {
            return "jqxComboBox";
          },
          _setInputValue: function (v) {
            this.changing = true;
            var curLabel = this.type.getLabel();
            this.inputNode[this.getJqxWidgetName()]('val', curLabel);
            this.changing = false;
          },
          getValue: function () {
            var v = $(this.inputNode).val();
            if (v === -1 || v === "")
              return null;
            return v;
          }
        }
      },
      Integer: {
        inherits: 'BaseInput',
        methods: {
          /* {value: null,
           decimal: 0,
           min: -99999999,
           max: 99999999,
           width: 200,
           validationMessage: "Invalid value",
           height: 25, textAlign: "right", readOnly: false,
           promptChar: "*",
           decimalDigits: 2,
           decimalSeparator: ".", groupSeparator: ",", groupSize: 3, symbol: "", symbolPosition: "left", digits: 8, negative: false, negativeSymbol: "-", disabled: false, inputMode: "advanced", spinButtons: false, spinButtonsWidth: 18, spinButtonsStep: 1, autoValidate: true, spinMode: "advanced", enableMouseWheel: true, touchMode: "auto", rtl: false, events: ["valueChanged", "textchanged", "mousedown", "mouseup", "keydown", "keyup", "keypress", "change"], aria: {"aria-valuenow": {name: "decimal", type: "number"}, "aria-valuemin": {name: "min", type: "number"}, "aria-valuemax": {name: "max", type: "number"}, "aria-disabled": {name: "disabled", type: "boolean"}}, invalidArgumentExceptions: ["invalid argument exception"]};
           */
          getDefaultInputOptions: function () {

            return {width: '100%', height: '100%', decimalDigits: 0, groupSeparator: "", promptChar: ""};
          },
          getJqxWidgetName: function () {
            return "jqxNumberInput";
          }
        }
      },
      Decimal: {
        inherits: 'BaseInput',
        methods: {
          getDefaultInputOptions: function () {
            var d = this.type.__definition;
            return {decimalDigits: d.NDECIMALS, digits: d.NINTEGERS};
          },
          getJqxWidgetName: function () {
            return "jqxNumberInput";
          }
        }
      },
      Text: {
        inherits: 'BaseInput',
        methods: {
          initialize: function (params) {
            var m = this;
            $(document).ready(function () {
              m.BaseInput$initialize(params);
            });
          },
          getDefaultInputOptions: function () {
            return {
              height: "400px"
            }
          },
          getJqxWidgetName: function () {
            return "jqxTextArea";
          }

        }
      },
      AutoIncrement: {
        //inherits: 'Siviglia.UI.Expando.View,Siviglia.Dom.EventManager',
        inherits: 'BaseInput',
        methods: {
          preInitialize: function (params) {
            this.value = params.type.getValue();
            this.BaseInput$preInitialize(params);
          },
          initialize: function (params) {

          },
          getValue: function () {
            return this.type.getValue();
          }
        }
      },
      Boolean: {
        inherits: 'BaseInput',
        methods: {
          getDefaultInputOptions: function () {
            return {width: 20, height: 20};
          },
          getJqxWidgetName: function () {
            return "jqxCheckBox";
          },
          _setInputValue: function (value) {
            if (value == null || value == "0" || value == "false" || value == false)
              this.inputNode.jqxCheckBox({checked: false});
            else
              this.inputNode.jqxCheckBox({checked: true});
          },
          getValue: function () {
            return this.inputNode.val() ? "1" : "0";
          }
        }
      },
      File:{
          inherits:'BaseInput',
          methods:{
            initialize:function(params){
              this.BaseInput$initialize(params);
              this._innerVal=null;
              this.inputNode.on('select', function (event) {
                var args = event.args;
                var fileName = args.file;
                var fileSize = args.size; // Note: Internet Explorer 9 and earlier do not support getting the file size.
                this._innerVal={fileName:fileName,fileSize:fileSize};
                this.type.setValue(this._innerVal);
              }.bind(this));
            },
            debounce: function (func, wait, immediate) {
              var timeout;
              return function () {
                var context = this, args = arguments;
                func.apply(context, args);
              };
            },
            getDefaultInputOptions:function(){

              return {
                autoUpload:false,
                multipleFilesUpload:false,
                fileInputName:this.type.__getFieldName()
              };
            },
            getJqxWidgetName(){
              return "jqxFileUpload";
            },
            _setInputValue:function(value)
            {
              this._innerVal=value;
              return null;
            },
            getValue: function () {
              return this._innerVal;
            }
          }
      },
      Image:{
          inherits:'File',
          methods:{
            getDefaultInputOptions:function(){
              let cur=this.File$getDefaultInputOptions();
              cur.accept="image/*";
              return cur;
            }
          }
      },
      Video:{
        inherits:'File',
        methods:{
          getDefaultInputOptions:function(){
            let cur=this.File$getDefaultInputOptions();
            cur.accept="video/*";
            return cur;
          }
        }
      },

      ComboBox: {
        inherits: 'BaseInput',
        destruct: function () {
          var s = this.type.__getSource();
          s.removeListeners(this);

        },
        methods: {
          preInitialize: function (params) {
            this.preprocessParams(params);
            this.source = null;
            this.dataBinding = false;
            this.preloaded = false;
            this.BaseInput$preInitialize(params);
          },
          initialize: function (params) {

            var opts = this.getDefaultInputOptions();
            //  for (var k in this.inputParams)
            //      opts[k] = this.inputParams[k];
            this.createInput(opts);
            this.inputNode[this.getJqxWidgetName()]('theme', 'light');
            // Hay 2 motivos para error de un combobox: 1) Valor requerido, 2) Invalid source.
            // Si es invalid source, al editar el input, ponemos el valor del tipo a null
            this.type.addListener("ERROR", null, function () {
              if (this.type.__sourceError !== null && this.type.getValue() !== null)
                this.type.setValue(null);
            }.bind(this), "ComboBox-Error");
            var m = this;
            //this.inputNode.on("change",function(ev){console.log("CHANGED")});
            this.inputNode.off("change");
            this.inputNode.on("change", function (ev) {
              if (m.dataBinding)
                return;
              if (!m.changing) {
                if (ev.args) {
                  m.changing = true;
                  var newVal = ev.args.item.value;
                  try {
                    if (newVal == -1) {
                      m.type.setValue(null);
                    } else {
                      m.type.setValue(m.getValue());
                    }
                    m.clearErrors();
                  } catch (e) {
                    m.showError(
                      Siviglia.i18n.es.base.getErrorFromJsException(e)
                    );
                  }
                  m.changing = false;
                } else {
                  m.changing = true;
                  $("input", m.inputNode).val(null);
                  try {
                    m.type.setValue(null);
                    m.clearErrors();
                  } catch (e) {
                    m.showError(
                      Siviglia.i18n.es.base.getErrorFromJsException(e)
                    );
                  }
                  m.changing = false;
                }
              } else {
                m._setInputValue(null);
              }
            });
            $("input", this.inputNode).on("focus", function () {
              if (this.disabled) {
                this.disable();
                return;
              } else {
                if (this.preloaded == false) {
                  this.preloaded = true;
                  this.refresh();
                }
              }
            }.bind(this));

          },
          detach: function () {
            this.BaseInput$detach();
            if (this.source)
              this.source.removeListeners(this);
          },
          preprocessParams: function (params) {
            this.type = params.type;
            var s = this.type.__getSource();
            this.labelField = s.getLabelExpression() ? "LABEL_EXPRESSION" : s.getLabelField();
            this.valueField = s.getValueField();
          },
          getValue: function () {
            var v = this.inputNode.jqxComboBox('val');
            if (v == '')
              v = null;
            return v;
          },
          getJqxWidgetName: function () {
            return "jqxComboBox";
          },
          save: function () {
            try {
              // Por los mismos motivos que se explican en onSourceChanged, si el tipo
              // tiene un error, no limpiamos el error en save(), no copiamos el valor actual
              // del input. Si no, lo que ocurre es que si se hace submit() dos veces de un formulario
              // que contiene un error en un tipo con source, la primera vez saldria el error, la segunda, no.
              if (!this.type.__getError()) {
                if (typeof this.inputNode !== "undefined" && !this.type.equals(this.getValue()))
                  this.type.setValue(this.getValue());
                this.clearErrors();
              }
            } catch (e) {
              this.showError(
                Siviglia.i18n.es.base.getErrorFromJsException(e)
              );
            }
          },
          getDefaultInputOptions: function () {
            var self = this;
            this.dataSource = {
              localdata: [],
              datatype: "array"
            };
            this.dataAdapter = new $.jqx.dataAdapter(self.dataSource);
            return {
              source: this.dataAdapter,
              openDelay: 200,
              autoComplete: true,
              autoBind: false,
              minLength: 1,
              remoteAutoComplete: this.type.__getSource().getDynamicField() !== null,
              displayMember: this.getLabelField(),
              valueMember: this.getValueField(),
              width: '100%',
              //height: '80%',
              autoItemsHeight: true,
              placeHolder: Siviglia.issetOr(this.inputParams["placeholder"], "--Seleccionar..."),
              search: function (str) {
                console.log("SEARCHING:" + str);
                self.source.search(str);
                // self.searchString = str;
                // self.refresh();
              }
            };
          },
          _setInputValue: function (toSet) {
            if (this.changing)
              return;
            this.changing = true;
            if (toSet == null) {

              this.inputNode.jqxComboBox('selectItem', -1);
              this.inputNode.jqxComboBox('val', '');
              this.type.setValue(null);

            } else {
              var item = this.inputNode.jqxComboBox('getItemByValue', toSet);
              if (item) {
                this.inputNode.jqxComboBox('val', item.label);
                setTimeout(function () {
                  this.changing = true;
                  this.inputNode.jqxComboBox('selectItem', item);
                  this.changing = false;
                }.bind(this), 100);
              }
            }
            this.changing = false;
          },
          getLabelField: function () {
            return this.labelField;
          },
          getValueField: function () {
            return this.valueField;
          },
          buildParameters: function () {
            var obj = {
              "search": this.searchString
            };
            if (this.sourceParameters) {
              for (var k in this.sourceParameters) {
                obj[k] = this.sourceParameters[k];
              }
            }
            return obj;
          },
          refresh: function () {
            if (this.disabled)
              return;
            var params = this.buildParameters();
            this.getSource(params);

          },
          getSource: function (params) {
            if (this.source == null) {
              //this.type.clear();
              var plainCtx = new Siviglia.Path.BaseObjectContext(this.controller, "*");
              var s = this.type.__getSource();
              s.addContext(plainCtx);
              s.addListener("CHANGE", this, "onSourceChanged", "Combobox-source");

              this.source = s;
            }
            this.source.fetch();
            return this.source;

          },
          onSourceChanged: function (evName, data) {
            var self = this;
            // Vemos ahora en data

            this.dataSource.localdata = [];
            if (data.value !== null) {
              this.dataSource.localdata = data.value;
              if (this.source.getLabelExpression() !== null) {
                for (var k = 0; k < data.value.length; k++)
                  data.value[k]["LABEL_EXPRESSION"] = this.source.getLabel(data.value[k])

              }
            }

            // La llamada a dataBind genera un problema en select anidados:
            // Si existen dos select anidados, S1 y S2, ocurre lo siguiente:
            // Inicialmente, S1 y S2 están en blanco. Se selecciona un item de S1. S2 sigue en blanco.
            // Se selecciona ahora un valor de S2.
            // Posteriormente, se cambia el valor de S1. El valor de S2 ahora deberia ponerse en blanco,
            // ya que su valor dependia del valor antiguo de S1.
            // En vez de eso, lo que ocurre es que al llamar a dataBind, se genera un evento "select", y se
            // autoselecciona el primer valor del nuevo set de datos asignado a S2.
            // Por eso, se marca que estamos haciendo el databind, para controlarlo en el gestor de eventos.
            this.dataBinding = true;

            self.dataAdapter.dataBind();

            this.dataBinding = false;
            if (self.type.is_set()) {
              var toSet = self.type.getValue();
              var it = self.inputNode.jqxComboBox('getItemByValue', toSet);
              if (it) {
                self._setInputValue(toSet);
              } else {
                // El motivo por el cual el tipo puede tener error, es porque se le haya
                // hecho un save().
                // Si el tipo que estamos gestionando, tiene un SOURCE que depende de un path,
                // y ese path no se puede resolver (algun elemento del path esta a null),
                // no se le pone un error al tipo.El tipo se pone, simplemente a null.
                // O sea, el combo se pondria automaticamente a null.O, cuando se carga el combo,
                // y se ve que el valor actual del tipo, no está en las opciones del combo,
                // se pone, de nuevo, a null.
                // Pero si se ha hecho un save() del tipo, es porque queremos enviarlo, o estamos en un formulario,
                // y no queremos que se ponga el valor a null, lo que haria que se borrara el error, y el formulario no
                // mostraria nada.
                if (self.type.__getError() === null) {
                  self.type.unset();
                }
                this.inputNode.jqxComboBox('selectItem', -1);

                //  this.inputNode.jqxComboBox('val','');
              }
            } else {
              //self._setInputValue(null);
              this.inputNode.jqxComboBox('selectItem', -1);
              //this.inputNode.jqxComboBox('val','');
            }
          }

        }
      },
      BooleanCombo: {
        inherits: "ComboBox",
        methods: {
          preInitialize: function (params) {
            this.source = null;
            this.dataBinding = false;
            this.labelField = "LABEL";
            this.valueField = "VALUE";
            this.BaseInput$preInitialize(params);
          },
          getSource: function (params) {
            if (this.source == null) {
              //this.type.clear();
              var stack = new Siviglia.Path.ContextStack();
              var factory = new Siviglia.Data.SourceFactory();
              var s = factory.getFromSource({
                "TYPE": "Array",
                "DATA":
                  [
                    {"LABEL": "--Seleccionar...", "VALUE": null},
                    {"LABEL": "True", "VALUE": true},
                    {"LABEL": "False", "VALUE": false}
                  ],
                "LABEL": "LABEL",
                "VALUE": "VALUE"
              }, this.type, stack);
              s.addListener("CHANGE", this, "onSourceChanged", "BooleanCombo-Source");
              this.source = s;
            }
            this.source.fetch();
            return this.source;
          }
        }
      },
      FixedSelect:
        {
          inherits: 'ComboBox',
          methods: {
            getDefaultInputOptions: function () {
              var self = this;
              this.dataSource = {
                localdata: [],
                datatype: "array"
              };
              this.dataAdapter = new $.jqx.dataAdapter(self.dataSource);
              return {
                source: this.dataAdapter,
                autoBind: true,
                displayMember: this.getSearchField(),
                valueMember: this.getValueField(),
                autoDropDownHeight: true,
                width: 200,
                height: 25,
                placeHolder: Siviglia.issetOr(this.inputParams["placeholder"], null)
              };
            }
          }
        },
      Relationship: {
        inherits: 'ComboBox'
      },
      Date: {
        inherits: 'BaseInput',
        methods:
          {
            getDefaultInputOptions: function () {
              // añadida propiedad para que el icono del calendario sea fluid 100%
              return {width: '100%'};
            },
            _setInputValue: function (toSet) {
              this.changing = true;
              //this.inputNode[this.getJqxWidgetName()]('val', toSet);
              $(this.inputNode).val(new Date(toSet));
              this.changing = false;
            },
            getJqxWidgetName: function () {
              return "jqxDateTimeInput";
            },
            getValue: function () {
              var val = this.inputNode.jqxDateTimeInput('value');
              if (val != null) {
                var v = val.getMonth() + 1;
                if (v < 10)
                  v = '0' + v;
                var d = val.getDate();
                if (d < 10)
                  d = '0' + d;
                var tt = val.getFullYear() + "-" + v + "-" + d;
                console.dir(tt);
                return tt;
              }
              return null;
            }
          }
      },
      DateTime: {
        inherits: 'Date',
        methods:
          {
            _setInputValue: function (toSet) {
              this.changing = true;
              //this.inputNode[this.getJqxWidgetName()]('val', toSet);
              $(this.inputNode).val(new Date(toSet));
              this.changing = false;
            },
            getValue: function () {
              var val = this.inputNode.jqxDateTimeInput('value');
              if (val != null) {
                var v = val.getMonth() + 1;
                if (v < 10)
                  v = '0' + v;
                var d = val.getDate();
                if (d < 10)
                  d = '0' + d;
                var h = val.getHours();
                if (h < 10)
                  h = '0' + h;
                var m = val.getMinutes();
                if (m < 10)
                  m = '0' + m;
                var s = val.getSeconds();
                if (s < 10)
                  s = '0' + s;

                return val.getFullYear() + "-" + v + "-" + d + " " + h + ":" + m + ":" + s;
              }
              return null;
            }
          }
      },
      Money: {
        inherits: 'Decimal'
      },
      Password: {
        inherits: 'String',
        methods: {
          getDefaultInputOptions: function () {
            var opts = {
              // width: 200,
              minLength: 0,
              placeHolder: Siviglia.issetOr(this.inputParams["placeholder"], "Password...")
            };
            return opts;
          },
          getJqxWidgetName: function () {
            return "jqxPasswordInput";
          }
        }
      },
      State: {
        inherits: 'Enum',
        methods: {
          onTypeChanged: function (event, params) {
            this.Enum$onTypeChanged(event, params);
            this.callInp(this.getDefaultInputOptions());
            var wasChanging = this.__changing;
            this.__changing = true;
            this.inputNode.val(this.type.getLabel());
            this.__changing = wasChanging;
          },
          getDefaultInputOptions: function () {
            var finalOpts = [];
            var stDef = this.type.__getController().getStateDef();
            var id = this.type.getValue();
            var label = stDef.getCurrentStateLabel();
            var isFinal = stDef.isFinalState(label);

            var d = this.type.__definition;
            for (var k = 0; k < d.VALUES.length; k++) {
              var c = d.VALUES[k];
              if (c == label || stDef.canTranslateTo(k)) {
                finalOpts.push(
                  {
                    //   html: '<div tabIndex=0 style="padding:1px">' + label + '</div>',
                    label: c,
                    value: c
                  }
                )

              }
            }
            var opts = {
              source: finalOpts, //finalOpts,
              placeHolder: '--Seleccionar...',
              autoComplete: false,
              height: 25,
              displayMember: "label",
              valueMember: "value",
              width: '100%',
              minLength: 0,
              disabled: isFinal
            };
            return opts;
          },
          getJqxWidgetName: function () {
            return "jqxComboBox";
          },
          _setInputValue: function (v) {
            this.changing = true;
            var curLabel = this.type.getLabel();
            this.inputNode[this.getJqxWidgetName()]('val', curLabel);
            this.changing = false;
          },

          save: function () {
            try {
              this.type.save();
              this.clearErrors();
              var opts = this.getDefaultInputOptions();
              this.callInp(opts);
            } catch (e) {
              this.showError(
                Siviglia.i18n.es.base.getErrorFromJsException(e)
              );
            }
          }
        }
      },
      Name: {
        inherits: 'String'
      },
      Street: {
        inherits: 'String'
      },
      City: {
        inherits: 'String'
      },
      Phone: {
        inherits: 'String'
      },
      UUID: {
        inherits: 'String'
      }, // Nuevos tipos de datos
      BankAccount: {
        inherits: 'String'
      },
      Color: {
        inherits: 'String'
      },
      Description: {
        inherits: 'String'
      },
      Email: {
        inherits: 'String'
      },

      IP: {
        inherits: 'String'
      },

      Link: {
        inherits: 'String'
      },
      ModelDatasourceReference: {
        inherits: 'String'
      },
      ModelField: {
        inherits: 'String'
      },
      NIF: {
        inherits: 'String'
      },
      PHPVariable: {
        inherits: 'String'
      },
      // Text:{
      //     inherits:'String'
      // },
      Timestamp: {
        inherits: 'Date'
      },
      UrlPathString: {
        inherits: 'String'
      },
      UserId: {
        inherits: 'String'
      },
      Model: {
        inherits: 'ComboBox'
      },
      Dictionary:
        {
          inherits: 'BaseInput',
          destruct: function () {


          },
          methods:
            {
              preInitialize: function (params) {
                this.title = "";
                this.self = this;
                this.hasSimpleType = false;
                this.description = "";
                this.BaseInput$preInitialize(params);
                this.currentKey = null;
                this.hasCurrentKey = false;
                if (this.type.getValue() === null)
                  this.type.setValue({});


                this.currentType = null;
                this.params = params;
                this.currentErrors = {};

              },

              initialize: function (params) {
              },
              _setInputValue: function (toSet) {

              },
              showPath: function (key) {
                // Showpath solo tiene que seleccionar la key del path, en caso de que
                // no fuera la seleccionada.
                if (typeof this.subWidgets[key] === "undefined") {
                  var node = $("[title='" + key + "']", this.rootNode);
                  this.onLabelClicked(node, {key: key});
                  /*this.currentKey = key;
                  if(this.hasCurrentKey===false)
                  this.hasCurrentKey = true;*/

                }
              },
              buildNewItemWidget: function (node, params) {
                if (this.newItemWidget) {
                  this.newItemWidget.destruct();
                }
                node.html("");
                var pp = this.params;
                pp.parentInput = this;
                var tempNode = $("<div></div>");
                this.newItemWidget = new Siviglia.inputs.jqwidgets.NewItem('Siviglia.inputs.jqwidgets.NewItem',
                  pp,
                  {},
                  tempNode,
                  this.__context
                );
                this.newItemWidget.__build().then(function () {
                  node.append(tempNode);
                })
              },
              onLabelClicked: function (node, params, evName, event) {
                // Hacemos esto para evitar doble repintado del widget, ya que hay
                // listeners para ambas variables.
                this.currentKey = params.key;
                this.currentType = this.type["*" + this.currentKey];
                this.hasCurrentKey = true;

                // Custom class for active element in Dictionary
                // Añadido data-sivId para evitar buscar en todo this.rootNode,
                // y solo buscar en el div que contiene las claves del diccionario.
                $(".seleccionado", this.SelectedKeyItem).removeClass("seleccionado");
                $(node).closest("li").addClass("seleccionado");

                if (typeof event !== "undefined")
                  event.stopPropagation();
              },
              onRemoveClicked: function (node, params, evName, event) {
                this.hasCurrentKey = false;
                this.currentKey = null;
                this.currentType = null;
                var v = this.type.getValue();
                delete v[params.key];
                event.stopPropagation();
                //this.type.removeItem(params.key);

              },
              onAdd: function (val) {
                this.type.addItem(val);
                this.onLabelClicked(null, {key: val});
              }
            }
        },
      Container:
        {
          inherits: 'Dictionary',
          destruct: function () {

            for (var k in this.registeredInputs)
              this.registeredInputs[k].input.destruct();

          },
          methods: {
            preInitialize: function (params) {

              this.description = "";
              this.hasGlobalErrors = false;
              this.Dictionary$preInitialize(params);
              this.globalErrors = [];


            },
            getGroups: function () {
              var groups = this.type.getGroups();
              if (groups == null) {
                return {"DEFAULT": {"LABEL": "Default", "FIELDS": this.type.getKeys()}};
              }
              return groups;
            },

            showPath: function (path) {
              // Un container, en un showPath, no deberia hacer nada...Ya que siempre muestra todos sus campos..
            }
          }
        },

      ByFieldContainer: {
        // El Container por campos, no muestra todos los campos a la vez, sino que
        // permite añadir o quitar campos disponibles.
        inherits: "Container",
        methods: {
          preInitialize: function (params) {
            /* Se van a utilizar 2 variables aqui, para mantener los campos usados, y los no usados.
            Los campos no usados, van a ser un SOURCE para un tipo String, que se va a mostrar en un combo.
            Los campos usados, se van a leer desde un sivLoop.
            Lo que queremos, es que no haya que hacer otra cosa que manejar esos dos arrays, insertando o eliminando
            elementos de cada uno.
            El array para el sivLoop lo puede mantener el widget. El sivloop añade listeners a este mismo array.
            Pero no pasa lo mismo con el SOURCE. El SOURCE de un campo, tiene que montar un Proxy sobre el array, y
            ese proxy se lo queda el SOURCE. es decir, en el código siguiente, el ArraySource no se va a quedar con
            el array "unusedFields", sino que va a hacer un Proxy sobre él. Es sobre ese Proxy sobre el que hay
            que añadir o quitar elementos. Y es por eso que, una vez creado el tipo (y por tanto, el SOURCE, y su proxy,
            recuperamos el array (proxy) del SOURCE, y es a ese al que añadimos y borramos elementos */

            var t = params.type;
            this.changing = false;
            this.currentElement = -1;
            var def = t.getDefinition();
            var v = t.getValue();
            var unusedFields = [];
            this.usedFields = [];
            for (var k in def["FIELDS"]) {
              if (v === null || Siviglia.empty(v[k]))
                unusedFields.push(k);
              else
                this.usedFields.push(k);
              def["FIELDS"][k]["KEEP_KEY_ON_EMPTY"] = false;
            }
            this.fieldsController = Siviglia.types.TypeFactory.getType("controller", {
              "TYPE": "String",
              "SOURCE": {
                "TYPE": "Array",
                "VALUES": unusedFields,
                "LABEL": "LABEL",
                "VALUE": "LABEL"
              }
            }, null, null);

            this.Container$preInitialize(params);

          },
          initialize: function (params) {
            this.Container$initialize(params);
            var factory = new Siviglia.inputs.jqwidgets.Factory()
            factory.getInput(this.fieldsController, {controller: this}, this.form).then(function (instance) {
              this.fControllerInput = instance;
              this.fControllerNode.append(instance.rootNode);
              this.fieldsController.addListener("CHANGE", this, "onFieldAdded", "ByFieldContainer");
              // Recuperamos el array (proxy).
              this.unusedFields = this.fieldsController.getDefinition().SOURCE.VALUES;
            }.bind(this));


          },
          onFieldAdded: function (evName, params) {
            // Hay que evitar bucles
            if (this.changing)
              return;
            this.changing = true;
            var selected = this.fieldsController.getValue();
            if (selected == null)
              return;
            var idx = this.unusedFields.indexOf(selected);
            if (idx >= 0) {
              this.unusedFields.splice(idx, 1);
              this.usedFields.push(selected);
            }
            this.currentElement = selected;
            this.changing = false;

          },
          setCurrent: function (node, params) {
            this.currentElement = params.current;
          },
          removeField: function (node, params) {
            var selected = params.current;
            if (selected == null)
              return;

            if (selected === this.currentElement) {
              // Importante destruir primero el subwidget, de forma que destruimos los listeners que
              // habia sobre los elementos que ahora vamos a destruir. Si no, ese subwidget puede
              // quedarse apuntando a un valor que ahora vamos a poner a null.

              this.subwidgets[selected].destruct();
              this.currentElement = '-1';
            }
            var idx = this.usedFields.indexOf(selected);
            if (idx >= 0) {
              this.usedFields.splice(idx, 1);
              this.unusedFields.push(selected);
              this.type[selected] = null;
            }

          }
        }
      },
      GridContainer: {
        inherits: "Container"
      },
      MenuContainer: {
        inherits: "Container",
        methods: {
          preInitialize: function (params) {
            this.Container$preInitialize(params);
            this.menu = params["MENU"];
            this.self = this;
          },
          initialize: function (params) {

            this.menuNode.jqxMenu({
              width: '100%', height: "100%"
              /* width: '600', height:"30px" */
            });
            this.menuNode.css({'visibility': 'visible'});

          }
        }


      },
      Menu: {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        methods: {
          preInitialize: function (params) {
            this.controller = params.controller;
            this.menu = params.menu;
          },
          initialize: function () {
          },
          getSubMenu: function (node, params) {
            var label = params.spec.Label;
            node.attr("href", "#");
            node.html(params.spec.Label);
            switch (params.spec.Type) {
              case "Menu": {
                var menuWidget = new Siviglia.inputs.jqwidgets.Menu('Siviglia.inputs.jqwidgets.Menu',
                  {controller: this.controller, menu: params.spec.Menu},
                  {},
                  $('<div></div>'),
                  this.__context
                );
                menuWidget.__build().then(function (instance) {
                  node.parent().append(menuWidget.rootNode);
                })
              }
                break;
            }

          }
        }

      },
      Array:
        {
          inherits: 'BaseInput',
          destruct: function () {
            if (this.newItemSelector)
              this.newItemSelector.destruct();
            if (this.newInstance)
              this.newInstance.destruct();
            if (this.currentItem) {
              this.currentItem.destruct();
            }
            this.currentSelection = null;
          },
          methods:
            {
              preInitialize: function (params) {
                this.BaseInput$preInitialize(params);
                this.value = params.type.getValue()
                this.newInstance = null;
                this.hasSimpleType = this.type.areContentsSimple();
                this.changing = false;
                this.currentItem = null;
              },
              initialize: function (params) {
                if (this.hasSimpleType)
                  this.buildNewItemWidget();
              },
              showPath: function (key) {
                this.waitComplete().then(function () {
                  // Se hace una copia propia
                  if (typeof this.subWidgets[key] === "undefined")
                    this.setSelected(key);

                }.bind(this));
              },
              onRemoveClicked: function (node, params, evName, event) {
                if (this.currentItem) {
                  this.currentItem.destruct();
                  this.currentItemNode.html("");
                }
                this.type.splice(params.key, 1);
                event.stopPropagation();
              },
              buildNewItemWidget: function (node, params) {
                this.newInstance = this.type.getValueInstance();
                var factory = new Siviglia.inputs.jqwidgets.Factory();
                factory.getInput(this.newInstance, {}, this.form).then(function (instance) {
                  if (this.newItemSelector) {
                    this.newItemSelector.destruct();
                  }
                  this.newItemNode.html("");
                  this.newItemSelector = instance;
                  this.newItemNode.append(this.newItemSelector.rootNode);
                }.bind(this));
              },
              onRemoveItem: function (node, params, evName, event) {
                var idx = params.index;
                var v = this.type.getValue();
                var curIdx=this.currentSelection;
                if (idx === this.currentSelection) {
                  if (this.currentItem) {
                    this.currentItem.destruct();
                    this.currentItemNode.html("");
                  }
                }
                v.splice(idx, 1);
                if (this.type.getValue().length > 0)
                  this.setSelected(0);
                else
                  this.currentItem=null;

                event.stopPropagation()
              },
              // Sobreescrito para evitar la llamada a la clase base.
              onTypeChanged: function () {

              },
              onNewItem: function () {

                var curVal = this.type.getValue();
                if (this.hasSimpleType) {
                  this.newItemSelector.changing = true;
                  this.newInstance.setValue(this.newItemSelector.inputNode.val());
                  if (!this.newInstance.__isEmpty()) {
                    var newValue = this.newInstance.getValue();
                    if (curVal === null)
                      this.type.setValue([newValue]);
                    else
                      this.type.getValue().push(newValue);
                  }

                  this.newItemSelector.inputNode.val("");
                  this.newItemSelector.changing = false;
                  this.newItemSelector.inputNode.focus();
                } else {
                  if (curVal === null)
                    this.type.setValue([null]);
                  else
                    this.type.getValue().push(null);
                  this.setSelected(this.type.getValue().length - 1);
                }
              },
              getInputFor: function (node, params, evName, event) {
                this.setSelected(params.key)
                event.stopPropagation();
              },
              setSelected: function (idx) {
                /*if (idx == this.currentSelection)
                  return;*/
                if (this.currentItem) {
                  this.currentItem.destruct();
                  this.currentItemNode.html("");
                }

                // Eliminacion de clases con dicho valor "array_selected"
                var elements_with_selected = document.getElementsByClassName("array_selected");
                for (var i = 0; i < elements_with_selected.length; i++) {
                  elements_with_selected[i].classList.remove("array_selected");
                }

                // insertamos el clase "array_selected" en el nodo adecuado.
                this.currentItemNode[0].previousElementSibling.children[idx * 2].classList.add("array_selected");

                this.currentSelection = idx;
                instance = new Siviglia.inputs.jqwidgets.SimpleInputContainer(
                  "Siviglia.inputs.jqwidgets.SimpleInputContainer",
                  {controller: this, form: this.form, key: idx, parent: this.type},
                  {},
                  $("<div></div>"),
                  new Siviglia.Path.ContextStack()
                );
                instance.__build().then(function () {
                  this.currentItem = instance;
                  this.currentItemNode.append(this.currentItem.rootNode);
                }.bind(this));
              }
            }
        },
      SourcedArray: {
        inherits: 'BaseInput',
        destruct: function () {
          this.type.getSource().removeListeners(this);
          if (this.valueListBox !== null)
            this.valueListBox.jqxListBox("destruct");
          if (this.sourceListBox !== null)
            this.sourceListBox.jqxListBox("destruct");

        },
        methods:
          {
            preInitialize: function (params) {
              this.BaseInput$preInitialize(params);
              this.source = this.type.getSource();
              this.valueListBox = null;
              this.sourceListBox = null;
              this.changing = false;

            },
            initialize: function (params) {

              // Los sources tienen un campo LABEL y un campo VALUE.
              // Pero en este input, si el LABEL y el VALUE no son el mismo campo,
              // el valor del tipo no es lo que queremos tener en el input, sino la
              // label correspondiente.
              // O sea, que hay que coger todos los valores del source, e ir, uno a uno, mirando si
              // tipo ya contiene el valor, o no. Si contiene el valor, incluir en el input la label asociada.

              // En el initialize, se van a quedar vacios ambos combos, hasta que se cargue la fuente.

              var preferences = this.getDefaultInputOptions();
              this.type.addListener("CHANGE", this, "onTypeChanged", "SourcedArray-Type");
              preferences.width = Siviglia.issetOr(preferences.width, 200);
              preferences.height = Siviglia.issetOr(preferences.height, 200);


              Object.assign(preferences,
                {
                  // si no se pone width o height, se coge valores por defecto
                  width: '250px',
                  height: '160px', // si altura más pequeña, se pone scroll derecho del div que contiene el listBoxContent
                  allowDrop: true,
                  allowDrag: true,
                  displayMember: "label",
                  valueMember: "value",
                  source: [],
                  //dragEnd: function (dragItem, dropItem) {
                  dragStart: function (dragItem, dropItem) {
                    this.changing = true;
                    var val = this.type.getValue();
                    var idx = val.indexOf(dragItem.value);
                    if (idx >= 0)
                      val.splice(idx, 1);
                    this.changing = false
                    console.dir(this.type.getValue());
                  }.bind(this),
                  /* renderer: function (index, label, value) {
                       return "<span>"+label+"</span>";
                   }.bind(this)*/
                }
              );
              this.valueListBox.jqxListBox(preferences);
              preferences = this.getDefaultInputOptions();
              preferences.width = Siviglia.issetOr(preferences.width, 200);
              preferences.height = Siviglia.issetOr(preferences.height, 200);
              Object.assign(preferences, {
                // si no se pone width o height, se coge valores por defecto
                // afecta solo al div listBoxContent (seleccion de items)
                width: '150px',
                height: '260px',
                allowDrop: true,
                allowDrag: true,
                source: [],
                displayMember: "label",
                valueMember: "value",
                dragEnd: function (dragItem, dropItem) {
                  this.changing = true;
                  if (!this.type.__hasOwnValue())
                    this.type.setValue([dragItem.value]);
                  else
                    this.type.getValue().push(dragItem.value);

                  this.changing = false;
                  console.dir(this.type.getValue());
                }.bind(this)/*,
                                    renderer: function (index, label, value) {
                                        return label;
                                    }.bind(this)*/
              });
              this.sourceListBox.jqxListBox(preferences);
              this.checkState();
              this.source = params.type.getSource();
              this.source.addListener("CHANGE", this, "onSourceChanged", "SourcedArray-source");
              this.source.fetch();
            },
            onSourceChanged: function (evName, params) {
              if (this.changing)
                return;
              var sourceData = [];
              var typeData = [];
              if (!params.valid) {
                this.disable();
              } else {
                if (this.type.__isEditable()) {
                  this.enable();
                  var assigned = this.getInputDataFromType(params.src);
                  sourceData = assigned.source;
                  typeData = assigned.type;
                }
              }
              var typeSource =
                {
                  datatype: "json",
                  datafields: [
                    {name: 'label'},
                    {name: 'value'}
                  ],
                  id: 'value',
                  localdata: typeData
                };
              var sourceSource =
                {
                  datatype: "json",
                  datafields: [
                    {name: 'label'},
                    {name: 'value'}
                  ],
                  id: 'value',
                  localdata: sourceData
                };
              console.log("TYPE:");
              console.dir(typeData);
              console.log("SOURCE:");
              console.log(sourceData);
              var typeDataAdapter = new $.jqx.dataAdapter(typeSource);
              var sourceDataAdapter = new $.jqx.dataAdapter(sourceSource);

              this.valueListBox.jqxListBox({"source": typeDataAdapter, displayMember: "label", valueMember: "value"});
              this.sourceListBox.jqxListBox({
                "source": sourceDataAdapter,
                displayMember: "label",
                valueMember: "value"
              });
            },
            onTypeChanged: function () {
              if (this.changing === true)
                return;
              // Hace un fetch dispara el CHANGE del source,
              // que dispara onSourceChanged, que cambiara las opciones.
              this.source.fetch();
            },
            getInputDataFromType: function (src) {

              var curVal = [];
              if (this.type.__hasOwnValue())
                curVal = this.type.__getSourcedValue();

              var valueField = this.source.getValueField();
              var unfiltered = this.source.getUnfiltered();
              var type = [];
              var source = [];
              for (var k = 0; k < unfiltered.length; k++) {
                var label = this.source.getLabel(unfiltered[k]);
                var value = unfiltered[k][valueField];
                var item = {"label": label, "value": value}
                if (curVal.indexOf(value) >= 0)
                  type.push(item);
                else
                  source.push(item);
              }
              return {source: source, type: type};
            },
            enable: function () {
            },
            disable: function () {
            }
          }
      },
      TypeSwitcher: {
        inherits: 'BaseInput',
        destruct: function () {
          if (this.typeSelector)
            this.typeSelector.destruct();
          if (this.typeSelectorWidget)
            this.typeSelectorWidget.destruct();
          if (this.subNode)
            this.subNode.destruct();
        },
        methods:
          {
            preInitialize: function (params) {
              this.BaseInput$preInitialize(params);
              this.subNode = null;
              this.currentType = null;
              // Se crea el objeto enum que son los tipos permitidos.
              var allTypes = params.type.getAllowedTypes();
              var data = [];
              for (var k in allTypes)
                data.push({"LABEL": k, "VALUE": k});
              var source = {
                "TYPE": "Array",
                "DATA": data,
                "LABEL": "LABEL",
                "VALUE": "VALUE"
              };

              var currentType = params.type.getCurrentAllowedType();

              this.typeSelector = Siviglia.types.TypeFactory.getType({fieldName: "selector", fieldPath: "/"}, {
                "TYPE": "String",
                "SOURCE": source
              }, params.type.__parent, currentType);

            },

            initialize: function (params) {

              var factory = new Siviglia.inputs.jqwidgets.Factory();


              factory.getInput(this.typeSelector, {controller: this}, this.form).then(
                function (instance) {
                  this.detach();
                  this.typeSelectorWidget = instance;
                  var m = this;
                  this.typeSelector.addListener("CHANGE", function (event, params) {
                    var val = m.typeSelector.getValue();
                    m.type.setCurrentType(val);
                    m.buildTypeUI();
                  }, "TypeSwitcher-type")

                  this.typeSwitchSelector.append(this.typeSelectorWidget.rootNode);
                  this.buildTypeUI();
                }.bind(this))
            },
            detach: function () {

              if (this.typeSelector !== null) {
                this.typeSelector.removeListeners(this);

              }
            },
            buildTypeUI: function () {
              var factory = new Siviglia.inputs.jqwidgets.Factory()

              this._curType = this.type.getCurrentType();

              if (this._curType) {
                var curTypeObj = this.type.getCurrentTypeObj();
                factory.getInput(curTypeObj, {controller: this}, this.form).then(function (instance) {
                  if (this.subNode) {
                    this.subNode.destruct();
                    this.fieldContainer.html("");
                  }

                  this.fieldContainer.append(instance.rootNode);
                  this.subNode = instance;
                }.bind(this));

              }

            },
            showPath: function (path) {
              // Un TypeSwitcher no tendria por que hacer nada en showPath
            },
            getTypeFromValue: function (val) {
              var typeField = Siviglia.issetOr(this.definition.TYPE_FIELD, null);
              if (typeField != null)
                return val[typeField];
              ;

              for (var ss in this.definition.TYPE_TYPE) {
                if (val.constructor.toString().match(ss) == ss)
                  return this.definition.TYPE_TYPE[ss].TYPE;
              }
            },
            _setInputValue: function (toSet) {

            },
            getValue: function () {
              if (this.subNode)
                return this.subNode.getValue();
              return null;
            },
            save: function () {
              if (this.subNode)
                return this.subNode.save();
              return null;
            },
            setType: function (typeName) {
              if (Siviglia.isset(this.definition.TYPE_FIELD)) {
                var c = {};
                c[this.definition.TYPE_FIELD] = typeName;
              }
              this.setValue(c);
            },
            getAllowedTypes: function () {
              var result = [];
              if (Siviglia.isset(this.definition.TYPE_FIELD)) {
                for (var k = 0; k < this.definition.ALLOWED_TYPES.length; k++) {
                  var n = this.definition.ALLOWED_TYPES[k];
                  result.push({LABEL: n, VALUE: n});
                }
              } else {
                for (var ss in this.definition.TYPE_TYPE) {
                  var cc = this.definition.TYPE_TYPE[ss];
                  result.push({name: cc.LABEL, value: cc.TYPE});
                }
              }
              return result;
            },
            getCurrentType: function () {
              if (Siviglia.isset(this.receivedValue)) {
                if (Siviglia.isset(this.definition.TYPE_FIELD)) {
                  var typeField = this.definition.TYPE_FIELD;
                  return this.receivedValue[typeField];
                } else {
                  for (var ss in this.definition.TYPE_TYPE) {
                    var cc = this.definition.TYPE_TYPE[ss];
                    if (this.receivedValue.constructor.toString().match(ss) != null)
                      return cc.TYPE;
                  }
                }
              }
              return this.currentType;
            },
            getSubNode: function () {
              return this.subNode;
            },
            __getCurrentPath: function (obj) {
              if (this.parent == null) return '/ROOT';
              return this.parent.__getCurrentPath(this);
            }
          }

      },
      NewItem:
        {
          inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
          destruct: function () {
            if (this.subInput) {
              this.subInput.destruct();
            }
          },
          methods:
            {
              preInitialize: function (params) {
                var typeDefinition = {
                  "TYPE": "String",
                  "MINLENGTH": 1
                };
                if (Siviglia.isset(params.type.__definition.SOURCE))
                  typeDefinition["SOURCE"] = params.type.__definition.SOURCE;

                // Sea el tipo que sea el que nos llega, lo convertimos a una String.
                typeDefinition["TYPE"] = "String";

                this.type = Siviglia.types.TypeFactory.getType({
                  fieldName: "selector",
                  fieldPath: "/"
                }, typeDefinition, params.type.__parent, null);
                this.parentInput = params.parentInput;
                this.subInput = null;
                this.params = params;
              },
              initialize: function () {
                //this.type.addListener("CHANGE",this,"paintInput");
                this.paintInput(null);
              },
              detach: function () {
                this.type.removeListeners(this);
                this.type.destruct();
              },
              paintInput: function (data) {
                if (this.subInput !== null) {
                  this.type = null;
                } else {
                  var factory = new Siviglia.inputs.jqwidgets.Factory();

                  factory.getInput(this.type, {controller: this}, this.form).then(function (instance) {
                    this.subInput = instance;
                    this.inputNode.html('');
                    this.inputNode.append(this.subInput.rootNode);
                  }.bind(this));
                }
              },
              onAdd: function () {
                this.errorNode.css({"display": "none"});
                try {
                  this.type.setValue(this.subInput.inputNode.val());
                  this.type.save();
                } catch (e) {
                  var message = Siviglia.i18n.es.base.getErrorFromJsException(e)
                  this.errorNode.css({"display": "block"});
                  this.errorNode.html(message);
                  return;
                }
                this.parentInput.onAdd(this.subInput.getValue());
                this.subInput.inputNode.val(null);
              }
            }
        },
      SimpleInputContainer: {
        inherits: "Siviglia.UI.Expando.View",
        destruct: function () {
          this.__deregister();

        },
        methods:
          {
            preInitialize: function (params) {
              this.form = params.form;
              this.innerInput = null;
              this.controller = null;
              // parent se refiere al parent type
              this.parent = params.parent;
              this.field = params.key;

              if (this.parent === null) {
                console.warn("WARNING:Se intenta mostrar un input para el  campo " + params.key + ", pero el valor del padre es nulo");
              }
              // controller se refiere al input o form que contiene este inputContainer
              this.controller = typeof params.controller === "undefined" ? this.form : params.controller;
              if (typeof this.parent.getPath !== "undefined") {
                this.innerType = this.parent.getPath("#*" + this.field);
              } else
                this.innerType = this.parent["*" + this.field];


              this.fieldName = params.key;
              this.fieldDef = this.innerType.getDefinition();
              this.innerInput = null;
              this.pathToShow = null;
              this.waitComplete().then(function () {
                this.form.registerInput(this, this.innerType);
              }.bind(this));


            },
            initialize: function () {
              if (this.controller)
                this.controller.addSubWidget(this.field, this);

              this.rootNode.addClass(this.fieldName);
              var typeDef = this.innerType.getDefinition();
              this.rootNode.addClass(typeDef["TYPE"]);

              this.inputNode.addClass(this.fieldName);
              var curError = this.innerType.__getError();
              if (curError) {
                this.showError(curError);
              }
            },
            rebuild: function () {
              this.__deregister();
            },
            __deregister: function () {
              if (this.innerInput !== null)
                this.innerInput.destruct();
              this.rootNode.html("");
              if (this.controller !== null)
                this.controller.removeSubWidget(this.field, this);
              this.form.unregisterInput(this);
            },
            focus: function () {
              setTimeout(function () {
                this.rootNode[0].scrollIntoView(true);
              }.bind(this), 50);
            },
            showPath: function (key) {
              if (this.innerInput) {
                if (key === this.innerInput.type.__name.fieldName)
                  this.rootNode[0].scrollIntoView(true);
                this.innerInput.showPath(key);
              } else
                this.pathToShow = key;

            },
            getInputFor: function (node, params) {
              var factory = new Siviglia.inputs.jqwidgets.Factory()
              //this.curType=this.type.getField(key);
              return factory.getInput(this.innerType, {controller: this, inputWrapper: this}, this.form).then(
                function (instance) {
                  if (this.innerInput != null) {
                    this.innerInput.destruct();
                    this.inputNode.html("");
                  }
                  this.innerInput = instance;
                  //this.innerInput.setInputWrapper(this);
                  // Nota: El listener de error, que antes estaba en el tipo, ahora esta aqui
                  this.innerInput.type.addListener("ERROR", this, "showError", "InputContainer-Error");
                  // Nota: Ademas, como ahora se muestra el error, tambien queremos saber cuándo cambia el tipo, para, si
                  // el valor de corrige, ocultar el campo de error. Por simplificar, se va a usar el mismo callback.
                  this.innerInput.type.addListener("CHANGE", this, "showError", "inputContainer-Change");

                  // comprobación que el inputWrapper no sea undefined para quitar error de la consola (https://pastebin.com/dyxxCW0c)
                  if (typeof instance.inputWrapper !== "undefined" && typeof instance.inputWrapper.errorNode !== "undefined")
                    this.errorNode = instance.inputWrapper.errorNode;

                  // Comprobación si un tipo es FIXED o no, para mostrarlo como string-raw o input
                  if (this.innerType.__definition.FIXED === this.innerType.__value) {
                    this.inputNode.text(this.innerType.__value); // String-raw del tipo si es Fixed
                  } else {
                    this.inputNode.append(instance.rootNode);// si no es string-raw->mostramos el input creado
                  }

                  /*var ce=this.currentErrors[key]
                  if(ce) {
                      for(var k=0;k<ce.length;k++) {
                          instance.addError(ce[k].path, ce[k].error);
                      }
                  }
                  delete this.currentErrors[key];*/
                  if (this.pathToShow !== null) {

                    instance.showPath(this.pathToShow);
                  }

                  this.pathToShow = null;

                }.bind(this));
            },
            getInner: function () {
              return this.innerInput;
            },
            clearErrors: function () {
              this.innerInput.clearErrors();
            },
            showError: function (e) {
              // Nota: Lo que queremos es que lo muestre esta misma clase. OJO, este mismo metodo se ha asociado a
              // ERROR y CHANGE, asi que le preguntamos el estado al tipo.
              var type = this.innerType;
              var errored = type.__isErrored();

              if (!errored) {
                // AQUI ACCEDEMOS AL NODO A TRAVES DE SIVID
                this.errorNode.css({"display": "none"});
              } else {
                this.displayError(Siviglia.i18n.es.base.getErrorFromJsException(type.__getError()));
              }
            },
            displayError: function (txt) {
              this.errorNode.css({"display": "block"});
              this.errorNode.html(txt);
            }

          }
      },
      StdInputContainer: {
        inherits: "SimpleInputContainer",
        methods:
          {
            preInitialize: function (params) {
              this.SimpleInputContainer$preInitialize(params);
              this.help = Siviglia.issetOr(this.fieldDef["HELP"], "");
              this.label = Siviglia.issetOr(this.fieldDef["LABEL"], "");
            },
            initialize: function (params) {
              this.SimpleInputContainer$initialize(params);

              this.labelNode.addClass(this.fieldName);
              if (this.help === "") {
                this.helpNode.addClass("hidden-help");
              }
              if (this.innerType.__isRequired()) {
                this.requiredNode.addClass("required");
              } else {
                // para forzar a no mostrar el div del nodo ni siquiera
                this.requiredNode[0].style.display = "none";
              }
            }
          }
      },
      StdFormButtons: {
        inherits: "Siviglia.UI.Expando.View",
        methods:
          {
            preInitialize: function (params) {

              this.form = params.form;
              if (typeof params.buttons === "undefined")
                this.buttons = [{"label": "Ok", "callback": "submit", "class": "button-ok"}];
              else
                this.buttons = params.buttons;

            },
            initialize: function () {
              for (var k = 0; k < this.buttons.length; k++) {
                var cs = this.buttons[k];
                var curB = $('<input type="button" value="' + cs.label + '">');
                (function (cs, curB, form) {
                  curB.on("click", function () {
                    form[cs["callback"]]();
                  }.bind(this));
                  if (typeof cs.class !== "undefined")
                    curB.addClass(cs.class);
                })(cs, curB, this.form);
                this.buttonContainer.append(curB);
              }
            },
            onclick: function (node, params) {
              this.form.submit();
            }
          }

      },
      Form: {
        inherits: "Container",
        destruct: function () {
          if (this._type)
            this._type.destruct();
          if (this.__containerWidget)
            this.__containerWidget.destruct();
        },
        methods:
          {
            preInitialize: function (params) {
              // La variable params puede ser:
              //   - objeto {model:..., form:..., keys:...}
              //   - objeto {bto: [objeto de tipo BTO]}
              // Si es un BTO, el formulario no esta asociado a un model/form, si no que simplemente se
              // edita un BaseTypedObject cuando, por ejemplo, es un filtro para un DataSource o un test.
              var loadedPromise = $.Deferred();
              var containerPromise = $.Deferred();
              var m = this;
              this.self = this;
              this.__model = null;
              this._type = null;
              this.registeredInputs = [];
              this.inputsByField = {};
              this.__erroredInputs = {};
              this.__containerWidget = null;
              this.__errorNode = null;
              this.__pathToShow = null;
              this.__pathToShowMaxMatch = 0;
              if (typeof params.model === "undefined") {
                this._type = params.bto;
                this.setupBto();
                loadedPromise.resolve(this._type);
              } else {
                this.__model = params.model;
                this.__formName = params.form;
                var descriptor = new Siviglia.Model.ModelDescriptor(params.model);
                var formUrl = descriptor.getFormUrl(params.form, params.keys);
                var transport = new Siviglia.Model.Transport();

                transport.doGet(formUrl).then(function (result) {
                  if (result.error == 0) {
                    this._type = new Siviglia.Model.Form(params.model, params.form, params.keys, result.form.definition, result.form.value);
                    this.setupBto();
                    loadedPromise.resolve(this._type);

                  } else
                    loadedPromise.reject();
                }.bind(this))
              }
              $.when(loadedPromise).then(function (bto) {
                this.onLoaded(bto);

                containerPromise.resolve();
              }.bind(this));
              return containerPromise;
            },
            // todo: crear el initialize con lo necesario para sobreescribir los estilos por defecto del Form, como
            // por ejemplo el texto del boton para submit, que es el ejemplo que ahora est'a incluido
            /*initialize:function(params)
            {
              this.Container$initialize(params);
              if(typeof params.style!=="undefined")
              {
                if(typeof params.style.button!=="undefined")
                {
                  if(params.style.button.text!=="undefined")
                    $("button",this.rootNode).attr("value",params.style.button.text)
                }
              }
            },*/
            onLoaded: function (bto) {
              this.Container$preInitialize({type: bto, form: this});
              this._form = this;
            },
            setupBto: function () {

              var ip = Siviglia.issetOr(this._type.__definition.INPUTPARAMS, {});
              this.__ip = [];
              for (var k in ip) {
                var t = k.replace(/\+/gm, '.*')
                t = t.replace(/\*/gm, '[^/]*');
                t = t.replace(/\//, '\\/');
                var r = new RegExp("^" + t + "$");
                this.__ip.push({reg: r, ip: ip[k]});
              }
            },
            getFormParams: function (typePath) {
              var curP = {};
              this.__ip.map(function (item) {
                if (typePath.match(item.reg))
                  curP = Siviglia.deepmerge(curP, item.ip);
              })
              return curP;
            },
            appendError: function (path, input) {
              this.__erroredInputs[path] = input;
            },
            clearError: function (path, input) {
              if (typeof this.__erroredInputs[path] !== "undefined")
                delete this.__erroredInputs[path];
            },
            addError: function (path, input) {
              this.appendError(path, input);
            },
            submit: function () {
              var errors = [];
              top.qq = this._type;
              if (this.hasGlobalErrors) {
                this.hasGlobalErrors = false;
                if (this.__errorNode !== null)
                  this.__errorNode.css({'display': 'none'});
              }

              for (var k = 0; k < this.registeredInputs.length; k++) {
                try {

                  this.registeredInputs[k].input.getInner().save();
                  this.registeredInputs[k].input.clearErrors();
                } catch (e) {
                  errors.push(e);
                  this.registeredInputs[k].input.showError(
                    Siviglia.i18n.es.base.getErrorFromJsException(e)
                  );
                }

              }

              try {
                this._type.save()
              } catch (e) {
                errors.push(e);
              }
              if (errors.length > 0) {
                for (var k = 0; k < errors.length; k++) {

                  if (!errors[k].path) {
                    console.error("ERROR DURANTE SAVE:" + errors[k].toString());
                  }

                  // fix error cuando se pulsa boton guardar (OK) en un modelo (https://pastebin.com/Ypync6F5)
                  if (typeof errors[k].path !== "undefined") {
                    var path = errors[k].path.split("/");
                    path.shift();
                    this.addError(path, errors[k]);
                    this.showPath("/" + path.join("/"));
                  }
                }
                return;
              }
              return this.onSubmit();
            },
            htmlSubmit:function(method,action,encType,extraFields)
            {
              //this.inputContainers[5].innerInput.__inherits.indexOf("File")
              let fData=new FormData();
              this.inputContainers.map(function(ip,idx){
                let inp=ip.innerInput;
                let name=ip.fieldName;
                // Por algun motivo, no es posible obtener el File del jqxFileUpload. No solo eso, sino que
                // dentro del widget hay 2 inputs de tipo File. Hay que coger el que tenga algo en su array de "files",
                // No voy a iterar tambien sobre ellos. Cojo el primer input, que parece ser el correcto.
                if(inp.__inherits.indexOf("File")>=0)
                {
                  let rawNodes=$("input[type=file]",inp.rootNode);
                  if(rawNodes[0].files.length>0)
                  {
                    fData.append(name,rawNodes[0].files[0])
                  }
                }
                else
                  fData.append(name,inp.getValue());
              })
              if(typeof extraFields!=="undefined")
              {
                for(let k in extraFields)
                {
                  fData.append(k,extraFields[k]);
                }
              }

              return $.ajax({
                url: action,
                type: method,
                data: fData,
                success: function (data) {
                  alert(data)
                },
                cache: false,
                contentType: false,
                processData: false
              });
            },
            doRedirect: function (type) {
              if (typeof this._type.__definition["REDIRECT"] !== "undefined" &&
                typeof this._type.__definition["REDIRECT"][type] !== "undefined" &&
                this._type.__definition["REDIRECT"][type] !== ""
              ) {
                var url = this._type.__definition["REDIRECT"][type];
                setTimeout(function () {
                  window.location.assign(url);
                }, 200);
                return true;
              }
              return false;

            },
            registerInput: function (input, type) {
              this.registeredInputs.push({input: input, type: type});
              this.inputsByField[type.__fieldNamePath] = input;
              if (this.__pathToShow !== null) {
                var spliced = type.__fieldNamePath.split("/");
                if (spliced[0] === "")
                  spliced.shift();
                var n = 0;
                while (n < this.__pathToShow.length && spliced[n] == this.__pathToShow[n])
                  n++;

                if (n >= this.__pathToShowMaxMatch) {
                  if (n === this.__pathToShow.length) {
                    input.focus();
                    this.__pathToShow = null;
                  } else {
                    this.__pathToShowMaxMatch = n;
                    input.showPath(this.__pathToShow[n]);
                  }
                }
              }
            },
            unregisterInput: function (input) {
              for (var k = 0; k < this.registeredInputs.length; k++) {
                if (this.registeredInputs[k].input === input) {
                  delete this.inputsByField[this.registeredInputs[k].type.__fieldNamePath];
                  this.registeredInputs.splice(k, 1);
                }
              }
            },
            getInputByPath: function (path) {
              return Siviglia.issetOr(this.inputsByField[path], null);
            },
            // A diferencia del resto de metodos showPath, en este caso, el path es una string.
            showPath: function (path) {
              // Hay que encontrar el widget con el path minimo al error.
              // Es decir, si el path es /a/b/c , tenemos que encontrar, si es posible, el input
              // asociado a /a , si no, a /a/b, y si no, el propio /a/b/c. Esto es necesario
              // para que se expandan los containers, en caso de que el error este en un input oculto.
              // Para encontrar ese minimo path, se reconstruye el path del error, y se hace un replace
              // de ese path, en el path de cada tipo, con "". La cadena minima resultante, es el input
              // que buscamos.
              this.waitComplete().then(function () {
                var rPath = path;
                var splicedPath = path.split("/");
                splicedPath.shift();
                var pathed = [];
                for (var k = 0; k < this.registeredInputs.length; k++) {
                  var fpath = this.registeredInputs[k].type.__fieldNamePath;

                  // Si el path del campo esta al principio del path del error
                  if (rPath.indexOf(fpath) === 0) {
                    pathed.push({idx: k, path: fpath});
                  }
                }
                pathed.sort(function (a, b) {
                  return a.path.length - b.path.length
                });
                var last = pathed.pop();
                var rem = rPath.replace(last.path, "");
                var inp = this.registeredInputs[last.idx];
                var totalPathParts = last.path.split("/");
                if (rem === "") {
                  var field = totalPathParts.pop();
                  inp.input.showPath(field);
                  return;
                }

                var remParts = rem.split("/");
                if (remParts[0] == "")
                  remParts.shift();
                var nextFieldToShow = remParts[0];

                totalPathParts.shift();
                var lastPathParts = last.path.split("/");

                this.__pathToShow = splicedPath;
                this.__pathToShowMaxMatch = lastPathParts.length - 1; // El "1" es porque en el array, el primer elemento es "", y no debe contarse

                inp.input.showPath(remParts[0]);


                // Ahora en minIndex tenemos el input con el indice minimo.
                // Pero queda aun un paso: hay que enviarle el path *a partir de el*, o sea,
                // si el error estaba en /a/b/c  y hemos encontrado /a/b, el path que le enviamos debe ser sólo "c"
                /*
            if(maxlen >= 0)
            {
                var found=maxVal.type.__fieldNamePath.split("/");
                if(found[0]=="")
                    found.shift();
                this.__pathToShow=splicedPath;
                this.__pathToShowMaxMatch=found.length-1;
                var curPath="";
                for(var k=0;k<found.length;k++)
                {
                    curPath+=("/"+found[k]);
                    this.registeredInputs[curPath].input.showPath(found[k]);
                }
            }
            else
            {
                // No se ha encontrado ningun input...Lo sacamos por consola
                console.error("Form: Path not found in showPath: "+path);
            }*/
              }.bind(this));
            },
            onSubmit: function () {
              this.globalErrors = [];
              this.hasGlobalErrors = false;
              var p = $.Deferred();
              if (this.__model === null) {
                this.fireEvent("SUBMIT", {});
                p.resolve();
              } else {
                this._type.submit().then(
                  function (r) {
                    if (r.error == 0) {
                      //this.type.setValue(r.data[0]);
                      var returned = r.data !== null ? r.data[0] : null;
                      this.onSuccess(returned);
                      if (!this.doRedirect("ON_SUCCESS"))
                        p.resolve(r);
                    } else {
                      this.parseErrors(r)
                      this.onError(r);
                      if (!this.doRedirect("ON_ERROR"))
                        p.reject(r);
                    }
                  }.bind(this),
                  function (error) {
                    this.parseErrors(error)
                    if (!this.doRedirect("ON_ERROR"))
                      p.reject(error)
                  }.bind(this)
                )
              }
              return p;
            },
            onSuccess: function (data) {

            },
            onError: function (errors) {

            },
            parseErrors: function (e) {
              if (e.error == 1) {
                var nFieldErrors = 0;
                if (e.action.fieldErrors) {
                  for (var k in e.action.fieldErrors) {
                    for (var c in e.action.fieldErrors[k]) {
                      for (var h in e.action.fieldErrors[k][c]) {
                        var error = e.action.fieldErrors[k][c][h];
                        var parts = error.path.split("/");
                        if (parts[0] == "")
                          parts.shift();
                        var regInp = this.getInputByPath(error.path);
                        regInp.displayError(
                          this.decodeServerError(c, e.action.fieldErrors[k][c])
                        )
                        this.addError(parts, error);

                      }
                    }
                  }
                }
                var isArray = toString.call(e.action.globalErrors) === "[object Array]";
                if (e.action.globalErrors && !isArray) {
                  this.decodeGlobalServerErrors(e.action.globalErrors)
                }
              } else {
                if (e.message)
                  this.fireEvent("GENERAL_ERROR", e.message + e.response.text);
              }
            },
            /*

                NOTA : ESTOS METODOS SE DEJAN AQUI, PERO TENDRAN QUE MOVERSE MUY POSIBLEMENTE
                A LOS WIDGETS, YA QUE SE DEDICAN A PINTAR
             */
            decodeGlobalServerErrors: function (ex) {
              var messages = [];
              for (var k in ex) {
                var parts = k.split("::");
                var exName = parts[1];
                var message = '';
                if (typeof this.type.__definition.ERRORS !== "undefined" &&
                  typeof this.type.__definition.ERRORS["GLOBAL"] !== "undefined" &&
                  typeof this.type.__definition.ERRORS["GLOBAL"][exName] !== "undefined")
                  messages.push(this.type.__definition.ERRORS["GLOBAL"][exName]["txt"]);
                else {
                  messages.push(Siviglia.issetOr(ex[k]["str"], exName));
                }
              }
              if (messages.length > 0) {
                this.globalErrors = messages;
                this.hasGlobalErrors = true;

                if (this.__errorNode !== null) {
                  this.__errorNode.html("");
                  var ul = $("<ul></ul>");
                  for (var j = 0; j < this.globalErrors.length; j++) {
                    var li = $("<li>" + this.globalErrors[j] + "</li>");
                    ul.append(li);
                  }
                  this.__errorNode.append(ul);
                  this.__errorNode.css({'display': 'block'});
                }

              }
            },
            decodeServerError: function (exceptionKey, exception) {

              var msg = Siviglia.i18n.es.base.getErrorFromServerException(exceptionKey, exception);
              if (msg) {
                return msg;
              }
              return "Unknown error";
            }
          }
      },
      AutoForm:{
        inherits:"Siviglia.inputs.jqwidgets.Form",
        destruct:function()
        {
          this.inputContainers.map(function(i){i.destruct()});
        },
        methods:{
          preInitialize:function(params)
          {
            this.inputContainers=[];
            this.Form$preInitialize(params);

          },
          initialize:function(params)
          {
             let q=this._type;
             let definition=q.getDefinition();
             for(let k in definition.FIELDS)
             {
              let n=$("<div></div>");
               let it=new Siviglia.inputs.jqwidgets.StdInputContainer('Siviglia.inputs.jqwidgets.StdInputContainer',
               {key:k, parent:this.type,form:this,controller:this},{},n,this.__context);
               this.formNode.append(n);
               it.__build();
               this.inputContainers.push(it);
             }
          }
        }
      },
      Confirm: {
        inherits: "Siviglia.UI.Expando.View,Siviglia.Dom.EventManager",
        destruct: function () {
          this.rootNode.jqxWindow('destroy');
        },
        methods: {
          preInitialize: function (params) {
            this.title = params.title;
            this.message = params.message;
          },
          initialize: function (params) {
            this.rootNode.jqxWindow({
              height: 100, //'auto',
              width: 350,
              autoOpen: false,
              isModal: true,
              okButton: this.buttonYes,
              cancelButton: this.buttonNo,
              initContent: function () {
                this.buttonYes.jqxButton({width: 40, height: 30});
                this.buttonNo.jqxButton({width: 40, height: 30});
                this.buttonNo.focus();

                this.buttonYes.click(function () {
                  this.fireEvent("CONFIRM", {"value": true});
                }.bind(this));
                this.buttonNo.click(function () {
                  this.fireEvent("CONFIRM", {"value": false});
                }.bind(this))
              }.bind(this)
            });
          },
          open: function () {
            this.rootNode.jqxWindow('open');
            this.rootNode.jqxWindow('focus');
          }
        }
      }
    }
  }
)
