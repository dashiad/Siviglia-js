Siviglia.Utils.buildClass(
    {
        context:'App.model.reflection.Model',
        classes:
            {
                Model:
                    {
                        inherits:'Siviglia.Model.Instance',
                        construct:function(meta,params)
                        {
                            this.layer=params.layer;
                            this.name=params.name;
                            this.summary=null;
                            this.Instance(meta);
                        },
                        methods:
                            {
                                getSummary:function()
                                {
                                    var m=this;
                                    if(this.summary!=null)
                                        return this.summary;
                                    return this.getDataSource("ObjectSummary",{class:this.layer+'/'+this.name}).then(function(d){
                                        m.setSummary(d);
                                    });
                                },
                                setSummary:function(s)
                                {
                                    this.summary=s;
                                },
                                getSummaryKey:function(key)
                                {
                                    return $.when(this.getSummary()).then(function(s){
                                        return s[key];
                                    })
                                },
                                getReflectionFormDefinition:function(type)
                                {
                                    return this.getDataSource("ReflectionFormDefinition",{type:type});
                                }
                            }
                    }
            }
    });
