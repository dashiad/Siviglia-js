{
    "ROOT"
:
    {
        "TYPE"
    :
        "DICTIONARY",
            "LABEL"
    :
        "Definition",
            "VALUETYPE"
    :
        "NODE",
            "LOAD_URL"
    :
        "http://127.0.0.1/Savoy/definitions.js"

    }
,
    "NODE"
:
    {
        "TYPE"
    :
        "TYPESWITCH",
            "TYPE_FIELD"
    :
        "TYPE",
            "LABEL"
    :
        "Node",
            "ALLOWED_TYPES"
    :
        [
            "*INTEGER",
            "*STRING",
            "*BOOLEAN",
            "*CONTAINER",
            "*DICTIONARY",
            "*ARRAY",
            "*SELECTOR",
            "*TYPESWITCH",
            "*OBJECTARRAY"
        ]
    }
,
    "INTEGER"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Integer",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "DEFAULT"
        :
            {
                "LABEL"
            :
                "Default Value", "TYPE"
            :
                "STRING", "HELP"
            :
                "Default value for field"
            }

        }
    }
,
    "STRING"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "String",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null or ''"
            }
        ,
            "DEFAULT"
        :
            {
                "LABEL"
            :
                "Default Value", "TYPE"
            :
                "STRING", "HELP"
            :
                "Default value for field"
            }

        }

    }
,
    "BOOLEAN"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Boolean",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "DEFAULT"
        :
            {
                "LABEL"
            :
                "Default Value", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "Default value for field"
            }
        }
    }
,
    "CONTAINER"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Container",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,

            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "LOAD_URL"
        :
            {
                "LABEL"
            :
                "Load URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If defined, this element will fill itself with values received from this datasource in JSON format"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null or empty"
            }
        ,
            "FIELDS"
        :
            {
                "LABEL"
            :
                "Fields",
                    "TYPE"
            :
                "DICTIONARY",
                    "HELP"
            :
                "Container Fields",
                    "VALUETYPE"
            :
                "NODE"
            }

        }
    }
,

    "DICTIONARY"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Dictionary",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "LOAD_URL"
        :
            {
                "LABEL"
            :
                "Load URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If defined, this element will fill itself with values received from this datasource in JSON format"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "SOURCE"
        :
            {
                "LABEL"
            :
                "Source", "TYPE"
            :
                "STRING", "HELP"
            :
                "If set, this defines the source for the allowed keys in this dictionary"
            }
        ,
            "FIELDS"
        :
            {
                "LABEL"
            :
                "Fields",
                    "TYPE"
            :
                "DICTIONARY",
                    "HELP"
            :
                "Container Fields",
                    "VALUETYPE"
            :
                "NODE"

            }
        }
    }
,
    "ARRAY"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Array",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                "y"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "LOAD_URL"
        :
            {
                "LABEL"
            :
                "Load URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If defined, this element will fill itself with values received from this datasource in JSON format"
            }
        ,
            "SAVE_URL"
        :
            {
                "LABEL"
            :
                "Save URL", "TYPE"
            :
                "STRING", "HELP"
            :
                "If this value is set, it will be the url  where this node value (indluding its children) will be POSTed to be saved."
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SOURCE"
        :
            {
                "LABEL"
            :
                "Source", "TYPE"
            :
                "STRING", "HELP"
            :
                "If set, this defines the source for the allowed values in this array"
            }
        ,
        }
    }
,

    "SELECTOR"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Selector",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "OPTIONS"
        :
            {
                "LABEL"
            :
                "Options", "REQUIRED"
            :
                true,
                    "TYPE"
            :
                "OBJECTARRAY",
                    "VALUETYPE"
            :
                "*OPTION"
            }
        }
    }
,
    "TYPESWITCH"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Type Switch",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "TYPE_FIELD"
        :
            {
                "LABEL"
            :
                "Type field", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "ALLOWED_TYPES"
        :
            {
                "LABEL"
            :
                "Allowed Types",
                    "TYPE"
            :
                "OBJECTARRAY",
                    "REQUIRED"
            :
                true,
                    "VALUETYPE"
            :
                {
                    "TYPE"
                :
                    "*ALLOW_TYPE"
                }
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        }
    }
,

    "OBJECTARRAY"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "LABEL"
    :
        "Object Array",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "VALUETYPE"
        :
            {
                "LABEL"
            :
                "Object type", "TYPE"
            :
                "*OBJARRAY_VALUE"
            }
        ,
            "HANDLER"
        :
            {
                "LABEL"
            :
                "Class Handler", "TYPE"
            :
                "STRING", "HELP"
            :
                "Instances of this class (in format a.b.c) will be created to handle this input events"
            }
        ,
            "REQUIRED"
        :
            {
                "LABEL"
            :
                "Required?", "TYPE"
            :
                "BOOLEAN"
            }
        ,
            "DESCRIPTION"
        :
            {
                "LABEL"
            :
                "Description", "TYPE"
            :
                "STRING", "HELP"
            :
                "Description to accompany this field"
            }
        ,
            "SET_ON_EMPTY"
        :
            {
                "LABEL"
            :
                "Set on empty", "TYPE"
            :
                "BOOLEAN", "HELP"
            :
                "If true, this element will be saved if it's null"
            }
        ,
            "HELP"
        :
            {
                "LABEL"
            :
                "Help", "TYPE"
            :
                "STRING", "HELP"
            :
                "Field Help"
            }
        }
    }
,

    "OPTION"
:
    {
        "TYPE"
    :
        "DICTIONARY",
            "FIELDS"
    :
        {
            "LABEL"
        :
            {
                "LABEL"
            :
                "Label", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "VALUE"
        :
            {
                "LABEL"
            :
                "Value", "TYPE"
            :
                "STRING", "REQUIRED"
            :
                true
            }
        ,
            "DEFAULT"
        :
            {
                "LABEL"
            :
                "Is default", "TYPE"
            :
                "BOOLEAN"
            }
        }
    }
,
    "ALLOW_TYPE"
:
    {
        "TYPE"
    :
        "CONTAINER",
            "FIELDS"
    :
        {
            "TYPE"
        :
            {
                "TYPE"
            :
                "SELECTOR",
                    "LABEL"
            :
                "Type",
                    "SOURCE"
            :
                "/",
                    "REQUIRED"
            :
                true
            }
        ,
            "LABEL"
        :
            {
                "TYPE"
            :
                "STRING",
                    "LABEL"
            :
                "Label",
                    "REQUIRED"
            :
                true
            }

        }
    }
,
    "OBJARRAY_VALUE"
:
    {


        "TYPE"
    :
        "SELECTOR",
            "LABEL"
    :
        "Type",
            "SOURCE"
    :
        "/",
            "REQUIRED"
    :
        true

    }
}


  
