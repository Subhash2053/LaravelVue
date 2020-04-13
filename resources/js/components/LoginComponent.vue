<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="error"
                dark
                flat
                
              >
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer />
                
               
              </v-toolbar>
              <v-card-text>
              <v-progress-linear
                      :active="loading"
                      :indeterminate="loading"
                      absolute
                      top
                      color="deep-purple accent-4"
                    ></v-progress-linear>

                <v-form
                ref="form"
                v-model="valid"
             
                >
                  <v-text-field
                  color="error"
                   :rules="emailRules"
                    label="Login"
                    name="login"
                    prepend-icon="mdi-account"
                    type="text"
                     v-model="email"
                  />

                  <v-text-field
                  color="error"
                    id="password"
                    :rules="passwordRules"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="password"
                  />
                </v-form>
                
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="error" :disabled="!valid" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>

     <v-snackbar
      v-model="snackbar"
    >
      {{ text }}
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
   data(){
     return {
       email: '',
        emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
       password: '',
        passwordRules: [
        v => !!v || 'Password is required',
       
      ],
       loading:false,
       snackbar:false,
       text:'',
       valid:true
     }
   },
   created() {
     this.$vuetify.theme.dark = true;
   },
   methods: {
     login:function(){
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


  axios.post('/api/login',{'email':this.email,
  'password':this.password
  })
  .then(res=>{
 localStorage.setItem('token',res.data.token);
 localStorage.setItem('logedIn',true);
  this.snackbar =true,
    this.text = "Login Successful"
    this.$router.push('admin')
    .then(res=>{console.log('Logged In')})
    .catch(err => {console.log('Error')})

  }).catch(err =>{
    this.snackbar =true,
    this.text = err.response.data.message
  })
      
     }
   },

  }
</script>