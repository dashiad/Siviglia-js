    Siviglia.Utils.buildClass({
        "context":"Siviglia.model.ads.AdManager.views",
        "classes":{
            SitesView:{
                "inherits":"Siviglia.UI.Expando.View",
                "methods":{
                    preInitialize:function(params)
                    {
                        var gtime=new Date().getTime();

                        this.domain=new Siviglia.model.BaseTypedObject(
                            {
                                "FIELDS":{
                                    "domain":{
                                        "LABEL":"Select Domain",
                                        "TYPE":"String",
                                        "SOURCE":{
                                            "TYPE":"DataSource",
                                            "MODEL":"/model/ads/AdManager",
                                            "DATASOURCE":"DomainsES",
                                            "LABEL":"domain",
                                            "VALUE":"domain",
                                            "PARAMS":{
                                                "start":gtime-24*60*60*1000,
                                                "end":gtime
                                            }
                                        }
                                    }
                                }
                            }
                        )
                    },
                    initialize:function(params){}
                }
            },
            List:{
                "inherits":"Siviglia.UI.Expando.View",
                destruct:function()
                {
                    if(this.ds)
                        this.ds.destruct();
                },
                "methods":{
                    preInitialize:function(params)
                    {

                        this.ds=null;
                        var model="/model/ads/AdManager";



                        this.jqgrid = null;
                        this.ds = new Siviglia.Model.DataSource(model, "SampleES", {domain:params.domain});
                        this.ds.freeze();
                        var gtime=new Date().getTime();
                        this.ds.params.start=gtime-24*60*60*1000;
                        this.ds.params.end=gtime;
                        this.ds.settings.__start = 0;
                        this.ds.settings.__count = 15;
                        this.ds.addListener("CHANGE", this, "refreshGrid");

                        this.parameters = this.ds["*params"];
                        this.parameters.__definition.INPUTPARAMS={
                            "/":{
                                "INPUT": "FlexContainer"
                            }
                        }

                    },
                    initialize:function(params)
                    {
                        if(this.ds)
                            this.ds.unfreeze(); // LLama automaticamente a refresh
                    },
                    refreshGrid:function(evName,params)
                    {
                        if(this.jqgrid===null) {
                            this.jqgrid=1;
                            return this.buildGrid();
                        }
                        this.jqxDataSource.localdata =  this.ds.getValue().data;

                        this.jqxDataSource.pagenum=this.ds.settings.__start;
                        this.jqxDataSource.pagesize=this.ds.settings.__count;
                        this.jqxDataSource.totalrecords=this.ds.settings.count;
                        this.dataAdapter.dataBind();
                    },
                    buildGrid:function(evName,params){

                        this.jqxDataSource = {
                            localdata: [],
                            datatype: "array",
                            cache:false,
                            pagenum:this.ds.settings.__start,
                            pagesize:this.ds.settings.__count,
                            totalrecords:this.ds.settings.count,

                            pager: function (pagenum, pagesize, oldpagenum) {
                                if(pageSize!=this.settings.__count)
                                {
                                    this.ds.freeze();
                                    this.settings.__count=this.ds.pageSize;
                                    this.settings.__start=0;
                                    this.ds.unfreeze();
                                    return;
                                }
                                if(pageNum!=this.settings.__start)
                                    this.settings.__start=pageNum;

                            },
                            //totalrecords: 1000000
                        };
                        this.dataAdapter = new $.jqx.dataAdapter(this.jqxDataSource);
                        this.jqxDataSource.localdata =  this.ds.getValue().data;

                        this.dataAdapter.dataBind();
                        var definition=this.ds.__getDefinition();
                        var columns=[];
                        for(var k in definition.FIELDS.data.ELEMENTS.FIELDS)
                        {
                            columns.push({text:k,datafield:k})
                        }

                        this.grid.jqxGrid(
                            {
                                source: this.dataAdapter,
                                pageable: true,
                                autoheight: true,
                                sortable: true,
                                altrows: true,
                                enabletooltips: true,
                                editable: false,
                                virtualmode: true,
                                rendergridrows: function(obj)
                                {
                                    return obj.data;
                                },
                                /* selectionmode: 'multiplecellsadvanced',

                                 rendergridrows: function () {
                                     return dataadapter.records;
                                 },*/

                                columns:columns
                            });
                        this.grid.on("pagechanged", function (event) {
                            this.ds.settings.__start=event.args.pagenum*this.ds.settings.__count;

                        }.bind(this));
                        this.grid.on("pagesizechanged", function (event) {
                            console.log("CHANGED22");
                        }.bind(this));
                    }

                }
            }
        }

    });
