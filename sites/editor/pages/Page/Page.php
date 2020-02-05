[*PAGE/EDITOR]
    [_TITLE]PAGE SECCION - v.1.0 beta Smartclip[#]
    [_CONTENT]
        <script src="http://statics.adtopy.com/Siviglia2.js"></script>
        <script src="http://statics.adtopy.com/test2.js"></script>

        <template id="my-element">
            <div>
            <div style="background-color:gray;color:white">

                <slot name="title">


                </slot>

            </div>

            <div style="background-color:yellow;color:black">
                <slot name="contents">

                </slot>
            </div>
            </div>
        </template>



        <template id="my-element2">
            <div style="width:200px;height:200px;margin:0px auto">
                <h1><slot name="title"></slot></h1>
            </div>
        </template>


        <my-element img>
            <img src=""
            <span slot="title" >
                Este es el titulo
            </span>
            <span slot="contents">
                <my-element2>
                    <span slot="title">Contenido!</span>
                </my-element2>
            </span>
            <span slot="contents">
                <my-element2>
                    <span slot="title">Contenido  dos!</span>
                </my-element2>
            </span>
        </my-element>


    [#]
[#]
