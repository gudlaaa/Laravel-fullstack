<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Managment <Button @click="addModal = true" v-if="isWritePermitted"><Icon type="md-add" />Add</Button></p>

					<div class="_overflow _table_div">
                     <editor 
                            ref="editor"
                            autofocus
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :init-data="initData"
                            @save="onSave"
                            />

                        <!-- <editor ref="editor" :config="config" :initialized="onInitialized"/> -->
                         

                        <Button type="primary" @click="save">Save the data</Button>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
export default {

	data(){
		return{
            config: {
                image: {
                    // Like in https://github.com/editor-js/image#config-params
                    field: 'image',
                    types: 'image/*',
                },
            },
            initData: null,
			data: {

            }
		}
	},
	methods: {
        onSave(response){
            console.log('this is onsave',response);
        },
        async save(){
            const res = await this.$refs.editor.save()
            console.log('res is this',res)
        }
    },
}
</script>