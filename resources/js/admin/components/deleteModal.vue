<template>
    <div>
        <!-- delete modal -->
        <Modal :value="getDeleteModalObj.showDeleteModal" 
        :mask-closable="false"
        :closable="false"
        width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>{{this.getDeleteModalObj.msg}}</p>
            </div>
            <div slot="footer">
                 <Button type="default" size="large" @click="closeModal">Close</Button>
                <Button type="error" size="large" :loading="isDeleting" :disabled="isDeleting" @click="deleteCategory">Delete</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
    data(){
        return {
            isDeleting: false,
        }
    },
    methods: {
        async deleteCategory() {
			// var categoryObj = this.deleteData;
			this.isDeleting = true
			const res = await this.callApi('post', this.getDeleteModalObj.deleteUrl, this.getDeleteModalObj.data) //categoryObj
			if (res.status == 200) {
				// this.categories.splice(categoryObj.index,1);
				// this.data.iconImage = categoryObj.iconImage
				// this.deleteImage();
				// this.deleteModal = false;
                this.s(getDeleteModalObj.successMsg)
                this.$store.commit('setDeleteModal', true)
			} else { 
                this.swr()
                this.$store.commit('setDeleteModal', false)
            }
            this.isDeleting = false
        },
        closeModal(){
            this.$store.commit('setDeleteModal', false)
        }
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    }
    
}
</script>