<template>
    <div>
        <div class="content">
			<div class="container-flud">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Admin Users <Button @click="addModal = true"><Icon type="md-add" />Add</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>FullName</th>
                                <th>Email</th>
                                <th>User Type</th>
								<th>Created Date</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, i) in this.users"  :key="i" v-if="users.length">
							<!-- <tr v-for="tag in tags" :key="tag.id" v-if="tags.length"> -->
								<td >{{user.id}}</td>
								<td class="_table_name">{{user.fullName}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.role_id}}</td>
								<td>{{user.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(user, i)">Edit</Button>
									<Button type="error" size="small" @click="showDeleteModal(user, i)" :loading="user.isDeleting" >Delete</Button>
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
					title="Add Admin"
					:mask-closable="false"
					:closable="false">

                    <div class="space">
                        <Input type="text" v-model="data.fullName" placeholder="Full Name"  />    
                    </div>
					<div class="space">
                        <Input type="email" v-model="data.email" placeholder="Email"  />    
                    </div>
                    <div class="space">
                        <Input type="password" v-model="data.password" placeholder="Password" />    
                    </div>
                    <div class="space">
                        <Select v-model="data.role_id" placeholder="Select admin type">
							 
                            <Option v-for="(role, i) in roles " :key="i" v-if="roles.length" :value="role.id" >{{role.roleName}}</Option>
                            
                        </Select>
                    </div>
                   
					<div slot="footer">
						<Button type="default" @click="addModal = false">Close</Button>
						<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Admin'}}</Button>
					</div>
				</Modal>
				<Modal
					v-model="editModal"
					title="Edit Admin"
					:mask-closable="false"
					:closable="false">
                    <div class="space">
                        <Input type="text" v-model="editData.fullName" placeholder="Full Name"  />    
                    </div>
					<div class="space">
                        <Input type="email" v-model="editData.email" placeholder="Email"  />    
                    </div>
                    <div class="space">
                        <Input type="password" v-model="editData.password" placeholder="Password" />    
                    </div>
					<!-- TODO edit remove hardcode values-->
                    <div class="space">
                        <Select v-model="editData.role_id" placeholder="Select admin type">
                            <Option value="Admin">Admin</Option>
                            <Option value="Editor">Editor</Option>
                        </Select>
                    </div>
					<!-- <Input v-model="editData.tagName" placeholder="Edit tag name" style="width: 300px" /> -->
					<div slot="footer">
						<Button type="default" @click="editModal = false">Close</Button>
						<Button type="primary" @click="editAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Admin'}}</Button>
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
                fullName: '',
                email: '',
                password: '',
				role_id: null
			},
			addModal: false,
			editModal: false,
			isAdding: false,
			deleteModal: false,
			isDeleting: false,
			users: [],
			editData: {
				tagName: '',
			},
			deleteData: {},
			roles: []
		}
	},
	methods: {
		async addAdmin () {
            if ( this.data.fullName.trim() == '') return this.e('Full name is required');
            if ( this.data.email.trim() == '') return this.e('Email is required');
            if ( this.data.password.trim() == '') return this.e('Password is required');
            if ( ! this.data.role_id ) return this.e('User type is required');
			const res = await this.callApi('post', 'app/create_user', this.data);
			console.log(res);
			if (res.status == 201){
				this.tags.unshift(res.data);
				this.s('Admin user has been added successfully');
				this.addModal = false;
				this.data.tagName = ''
			} else {
				if(res.status == 422){
                    for(let i in res.data.errors ) {
                        this.e(res.data.errors[i][0])
                    }
				} else {
					this.swr()
				}
				
			}
		},
		async editAdmin () {
			if ( this.editData.fullName.trim() == '') return this.e('Full name is required');
            if ( this.editData.email.trim() == '') return this.e('Email is required');
            if ( ! this.data.role_id ) return this.e('User type is required');
			const res = await this.callApi('post', 'app/edit_user', this.editData);
			console.log(res);
			if (res.status == 200){
				this.users[this.editData.index] = this.editData
				this.s('Tag has been edited successfully');
				this.editModal = false;
			} else {
				if(res.status == 422){
					for(let i in res.data.errors ) {
                        this.e(res.data.errors[i][0])
                    }
				} else {
					this.swr()
				}
				
			}
		},
		showEditModal(user, index){
			var obj = {
                id: user.id,
                fullName: user.fullName,
                email: user.email,
                role_id: user.role_id,
				index: index
			}
			this.editData = obj
			this.editModal = true
		},
		showDeleteModal(tagObj, index){
			// var obj = {
			// 	id: tagObj.id,
			// 	tagName: tagObj.tagName,
			// 	index: index
			// }

			const deleteModalObj = {
				showDeleteModal: true,
				deleteUrl: 'app/delete_tag',
				data: tagObj,
				deletingIndex: tagObj.id,
				isDeleted: false
			}

			this.$store.commit('showDeleteModalObj', deleteModalObj)
			console.log('delete Method is called')
		},
		async deleteTag() {
			var tagObj = this.deleteData;
			this.$set(tagObj, 'isDeleting', true)
			const res = await this.callApi('post', 'app/delete_tag', tagObj)
			if (res.status == 200) {
				this.tags.splice(tagObj.index,1);
				this.deleteModal = false;
				this.s('Tag has been deleted successfully')
			} else {
				this.swr()
			}
		}
		
	},
	async created(){
		const [ res, resRole] = await Promise.all([
			this.callApi('get', 'app/get_users', ''),
			this.callApi('get', 'app/get_roles', '')
		])
		//console.log(res.data);
		if (res.status == 200){
			this.users = res.data;
		} else {
			this.swr()
		}

		if (resRole.status == 200){
			this.roles = resRole.data;
		} else {
			this.swr()
		}
	},
	components: {
		deleteModal
	}
}
</script>
