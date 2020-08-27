<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Managment <Button @click="addModal = true" v-if="isWritePermitted"><Icon type="md-add" />Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Role Type</th>
								<th>Created Date</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(role, i) in this.roles"  :key="i" v-if="roles.length">
							<!-- <tr v-for="tag in tags" :key="tag.id" v-if="tags.length"> -->
								<td >{{role.id}}</td>
								<td class="_table_name">{{role.roleName}}</td>
								<td>{{role.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(role, i)" v-if="isUpdatePermitted">Edit</Button>
									<Button type="error" size="small" @click="showDeleteModal(role, i)" :loading="role.isDeleting" v-if="isDeletePermitted">Delete</Button>
									<!-- <button class="_btn btn-primary" type="button">Edit</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button> -->
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
				 <Page :total="100" />
				<Modal
					v-model="addModal"
					title="Add Role"
					:mask-closable="false"
					:closable="false">
					<Input v-model="data.roleName" placeholder="Add Role name" style="width: 300px" />
					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="add" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Role'}}</Button>
					</div>
				</Modal>
				<Modal
					v-model="editModal"
					title="Edit Tag"
					:mask-closable="false"
					:closable="false">
					<Input v-model="editData.roleName" placeholder="Edit tag name" style="width: 300px" />
					<div slot="footer">
						<Button type="default" @click="editModal = false">Close</Button>
						<Button type="primary" @click="edit" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Role'}}</Button>
					</div>
				</Modal>
				<!-- delete modal -->
				<!-- <Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete this tag?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long :loading="false" :disabled="false" @click="deleteTag">Delete</Button>
					</div>
				</Modal> -->
				<deleteModal></deleteModal>

			</div>
		</div>
    </div>
</template>

<script>
import deleteModal from '../components/deleteModal'
import { mapGetters } from 'vuex';
export default {

	data(){
		return{
			data: {
				roleName: '',	
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			deleteModal: false,
			isDeleting: false,
			roles: [],
			editData: {
				roleName: '',
			},
			deleteData: {},
		}
	},
	methods: {
		async add() {
			if ( this.data.roleName.trim() == '') return this.e('Role name is required');
			const res = await this.callApi('post', 'app/create_role', {roleName: this.data.roleName});
			console.log(res);
			if (res.status == 201){
				this.roles.unshift(res.data);
				this.s('Role has been added successfully');
				this.addModal = false;
				this.data.roleName = ''
			} else {
				if(res.status == 422){
					if(res.data.errors.roleName){
						this.e(res.data.errors.roleName[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		async edit() {
			if ( this.editData.roleName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/edit_role', this.editData);
			console.log(res);
			if (res.status == 200){
				this.roles[this.editData.index].roleName = this.editData.roleName
				this.s('Tag has been edited successfully');
				this.editModal = false;
			} else {
				if(res.status == 422){
					if(res.data.errors.roleName){
						this.e(res.data.errors.roleName[0])
					}
				} else {
					this.swr()
				}
				
			}
		},
		showEditModal(roleObj, index){
			var obj = {
				id: roleObj.id,
				roleName: roleObj.roleName,
				index: index
			}
			this.editData = obj
			this.editModal = true
		},
		showDeleteModal(roleObj, index){
			// var obj = {
			// 	id: roleObj.id,
			// 	roleName: roleObj.roleName,
			// 	index: index
			// }

			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_role',
				data: roleObj,
				deletingIndex: roleObj.id,
				isDeleted: false
			}

			this.$store.commit('showDeleteModalObj', deleteModalObj)
			console.log('delete Method is called')
		},
		async deleteTag() {
			var roleObj = this.deleteData;
			this.$set(roleObj, 'isDeleting', true)
			const res = await this.callApi('post', 'app/delete_role', roleObj)
			if (res.status == 200) {
				this.roles.splice(roleObj.index,1);
				this.deleteModal = false;
				this.s('Tag has been deleted successfully')
			} else {
				this.swr()
			}
		}
		
	},
	async created(){
		const res = await this.callApi('get', 'app/get_roles', '');
		//console.log(res.data);
		if (res.status == 200){
			this.roles = res.data;
		} else {
			this.swr()
		}
	},
	components: {
		deleteModal
	}
}
</script>