<template>

    <transition name="modal">

        <div class="vlModalMask" ref="modal" 
            tabindex="0"
            @keydown.left="$emit('previous')"
            @keydown.right="$emit('next')"
             @click="close" v-if="opened" 
            :style="{'z-index': zIndex - 2 }">

            <div class="vlModalClose" 
                @click.stop="closeAction" 
                :style="{'z-index': zIndex + 2 }">
              <i class="icon-times-circle"></i>
            </div>

            <i v-if="arrows" class="vlModalBtn icon-chevron-left" 
                @click.stop="$emit('previous')" />

            <i v-if="arrows" class="vlModalBtn icon-chevron-right" 
                @click.stop="$emit('next')" />

            <div class="vlModalWrapper">

                <div 
                    class="vlModalContainer" 
                    :id="'vlModalContainer'+name"
                    :style="{'width': width}">

                    <vl-panel 
                        v-show="ajaxContent"
                        :id="panelId" />
                
                    <slot />

                </div>
                
            </div>

        </div>
        
    </transition>
</template>

<script>
    export default {
        props: ['name', 'width', 'warn', 'arrows'],
        data(){
            return {
                opened : false,
                ajaxContent: false,
                zIndex: 2000,
                panelId: this.name !='default' ? this.name : 'vlDefaultModal',
                warnData: false
            }
        },
        computed: {
            warnbeforeclose(){ return this.warn || this.warnData }
        },
        methods:{
            close: function(e) {
                if (!$(e.target).hasClass('vlModalContainer') 
                    && !$(e.target).parents('#vlModalContainer'+this.name).length){

                    if(!this.warnbeforeclose || (this.warnbeforeclose && confirm(this.warnbeforeclose))){    
                        this.closeAction()
                    }

                    e.stopPropagation() //so that parent modals don't close too
                }
            },
            closeAction(){
                this.opened = false
                this.$emit('closed')
            },
            open(ajaxContent){
                this.opened = true
                this.ajaxContent = ajaxContent ? true : false
                this.$emit('opened')
                //applies zIndex to the vlModalClose higher if in another modal
                this.$nextTick(()=> {
                    var currentElem = $(this.$refs.modal), depth = 0
                    while(currentElem.closest('.vlModalWrapper').length){
                        depth += 1
                        currentElem = currentElem.closest('.vlModalWrapper').eq(0).parent()
                    }
                    this.zIndex += depth*100
                })
            }
        },
        mounted(){
            this.$modal.events.$on('show', (modalName, ajaxContent, warnbeforeclose) => {
                if(modalName == this.name){
                    this.warnData = warnbeforeclose || false
                    this.open(ajaxContent)
                    this.$nextTick(() => this.$refs.modal.focus()) //to be able to use keydown events
                }
            })
            this.$modal.events.$on('close', (modalName) => {
                if(modalName == this.name)
                    this.closeAction()
            })

            this.$modal.events.$on('showFill'+this.name, (html) => {
                this.open(true)
                this.$nextTick(()=> {
                    this.$vuravel.vlFillPanel(this.panelId, html)
                })
            })
        }
    }
</script>
