<template>
    <v-data-table item-key="name" class="elevation-1" 
    :loading="loading"
    color="red"
     loading-text="Loading... Please wait"
     :headers="headers"
     :items="roles"
     >
    
     <template v-slot:top>
      <v-toolbar flat >
        <v-toolbar-title>Roles</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on">Add New Role</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-text-field v-model="editedItem.name" label="Role Name"></v-text-field>
                  </v-col>
                
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
       <v-snackbar
                v-model="snackbar"
              >
              {{text}}
                
                <v-btn
                  color="error"
                  text
                  @click="snackbar = false"
                >
                  Close
                </v-btn>
              </v-snackbar>
    </template>
    <template v-slot:no-data>
    
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
    </v-data-table>
</template>

<script>
 export default {
    data: () => ({
      dialog: false,
      loading:false,
      snackbar:false,
      text:'',
      headers: [
        {
          text: '#',
          align: 'start',
          sortable: false,
          value: 'id',
        },
        { text: 'Name', value: 'name' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
       
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      roles: [],
      editedIndex: -1,
      editedItem: {
          id:'',
        name: '',
        created_at:'',
        updated_at:''
      },
      defaultItem: {
                 id:'',
        name: '',
        created_at:'',
        updated_at:''
       
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
    
        // Add a request interceptor
        axios.interceptors.request.use( (config)=> {
        this.loading = true;
            return config;
        }, (error) =>{
            this.loading = false;
            // Do something with request error
            return Promise.reject(error);
        });

        // Add a response interceptor
        axios.interceptors.response.use( (response)=> {
            this.loading = false;
            return response;
        },  (error) =>{
            this.loading = false;
            return Promise.reject(error);
        });


        axios.get('/api/roles',{})
        .then(res=>{
        this.roles= res.data.roles
      
        }).catch(err =>{
            if(err.response.status == 401){
                localStorage.removeItem('token')
                this.$router.push('/login')
            }
        })

    
       
      },

      editItem (item) {
        this.editedIndex = this.roles.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.roles.indexOf(item)
        let decide = confirm('Are you sure you want to delete this item?')
        if(decide){
        axios
          .delete("/api/roles/" + item.id, {})
          .then(res => {
            this.text = "Record Deleted Successfully!";
            this.snackbar = true;
            this.roles.splice(index, 1);
          })
          .catch(err => {
            console.log(err.response);
            this.text = "Error Deleting Record";
            this.snackbar = true;
          });
        }

    
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        
        if (this.editedIndex > -1) {
        const index = this.editedIndex;
        axios
          .put("/api/roles/" + this.editedItem.id, this.editedItem)
          .then(res => {
            this.text = "Record Updated Successfully!";
            this.snackbar = true;
            Object.assign(this.roles[index], res.data.role);
          })
          .catch(err => {
            console.log(err.response);
            this.text = "Error Updating Record";
            this.snackbar = true;
          });
        } else {
         
             axios.post('/api/roles',
            this.editedItem
            )
            .then(res=>{
                 this.text = "Record Created Successfully!";
            this.snackbar = true;
           this.roles.push(res.data.role);

            }).catch(err =>{
               this.text = "Error Creating Record";
            this.snackbar = true;
            })
        }
        this.close()
      },
    },
  }
</script>

<style scoped>
    
</style>