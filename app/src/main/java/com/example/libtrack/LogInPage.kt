package com.example.libtrack

import android.content.Context
import android.util.Log
import android.widget.Toast
import androidx.compose.foundation.Image
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.offset
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TextField
import androidx.compose.material3.TextFieldDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.focus.FocusRequester
import androidx.compose.ui.focus.focusRequester
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.FontFamily
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewModelScope
import androidx.navigation.NavHostController
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.launch
import okhttp3.OkHttpClient
import retrofit2.Response
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.Body
import retrofit2.http.POST
import java.io.IOException
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import kotlinx.coroutines.flow.MutableSharedFlow
import kotlinx.coroutines.flow.asSharedFlow



@Composable
fun LogIn(navController: NavHostController) {
    // Context and View Model
    val context = LocalContext.current
    val loginViewModel = remember { LoginViewModel(context) } // Initialize ViewModel here

    // For text fields / Text State
    var studentIdTS by remember { mutableStateOf("") }
    var passwordTS by remember { mutableStateOf("") }

    // For error handling / Booleans
    var isStudentId by remember { mutableStateOf(true) }
    var isPassword by remember { mutableStateOf(true) }
    var isRegistered by remember { mutableStateOf(true) }

    // For text field focus
    val studentIdFocusRequester = remember { FocusRequester() }
    val passwordFocusRequester = remember { FocusRequester() }

    LaunchedEffect(key1 = true) { // Use LaunchedEffect to collect navigation events
        loginViewModel.navigationEvent.collect { route ->
            navController.navigate(route)
        }
    }


    Scaffold(
        modifier = Modifier.fillMaxSize()
    ) {paddingValues ->

        LazyColumn (
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally,
            modifier = Modifier
                .padding(paddingValues)
                .fillMaxSize()
        ){
            items(1){

                // Logo
                Image(
                    painter = painterResource(id = R.drawable.logo),
                    contentDescription = "Logo Loading",
                    modifier = Modifier
                        .size(120.dp)
                        .clip(CircleShape)
                )

                Spacer(
                    modifier = Modifier
                        .height(50.dp)
                )

                // Title
                Text(
                    text = "Welcome to LibTrack",
                    style = TextStyle(
                        color = Color.Black,
                        fontSize = 28.sp,
                        fontWeight = FontWeight(700),
                        fontFamily = FontFamily.Default
                    )
                )

                Spacer(
                    modifier = Modifier
                        .height(15.dp)
                )

                // Subtitle
                Text(
                    text = "UPang LibTrack - Your literary companion",
                    style = TextStyle(
                        color = Color.Gray,
                        fontSize = 17.sp,
                        fontWeight = FontWeight(400),
                        fontFamily = FontFamily.Default,
                    )
                )

                Spacer(
                    modifier = Modifier
                        .height(23.dp)
                )

                Text(
                    modifier = Modifier
                        .offset(
                            (-131).dp, 0.dp),
                    text = "STUDENT ID",
                    fontSize = 12.sp,
                    color = Color(0xFF727D83)
                )

                // ------------------------------------------------------------ STUDENT ID ------------------------------------------------------------
                TextField(
                    modifier = Modifier
                        .border(
                            width = 1.2.dp,
                            color = if(!isStudentId || !isRegistered) Color.Red else Color(0xFF727D83),
                            shape = RoundedCornerShape(12.dp)
                        )
                        .width(350.dp)
                        .focusRequester(studentIdFocusRequester),
                    colors = TextFieldDefaults.colors(
                        focusedContainerColor = Color.Transparent,
                        unfocusedContainerColor = Color.Transparent,
                        focusedIndicatorColor = Color.Transparent,
                        unfocusedIndicatorColor = Color.Transparent
                    ),
                    textStyle = TextStyle(
                        fontSize = 17.sp
                    ),
                    placeholder = {
                        Text(
                            text = "Enter Student ID",
                            fontWeight = FontWeight(400))
                    },

                    singleLine = true,
                    value = studentIdTS,
                    onValueChange = { if (it.length <= 14) studentIdTS = it },
                    keyboardOptions = KeyboardOptions(
                        keyboardType = KeyboardType.Number,
                        imeAction = ImeAction.Next
                    ),
                    keyboardActions = KeyboardActions(
                        onDone = {passwordFocusRequester.requestFocus()}
                    ),

                )

                if (!isStudentId){
                    Text(
                        text ="Student ID is required.",
                        modifier = Modifier
                            .offset(
                                (-100).dp, 5.dp),
                        style = TextStyle(
                            color = Color.Red,
                            fontSize = 13.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default,
                        ),
                    )
                }

                if (!isRegistered){
                    Text(
                        text ="Invalid Student ID.",
                        modifier = Modifier
                            .offset(
                                (-111).dp, 5.dp),
                        style = TextStyle(
                            color = Color.Red,
                            fontSize = 13.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default,
                        ),
                    )
                }

                Spacer(
                    modifier = Modifier
                        .height(if (!isStudentId || !isRegistered) 17.dp else 22.dp)
                )

                Text(
                    modifier = Modifier
                        .offset(
                            (-133).dp, 0.dp),
                    text = "PASSWORD",
                    fontSize = 12.sp,
                    color = Color(0xFF727D83)
                )

                // ------------------------------------------------------------ PASSWORD ------------------------------------------------------------
                TextField(
                    modifier = Modifier
                        .border(
                            width = 1.2.dp,
                            color = if(!isPassword || !isRegistered) Color.Red else Color(0xFF727D83),
                            shape = RoundedCornerShape(12.dp)
                        )
                        .width(350.dp),
                    colors = TextFieldDefaults.colors(
                        focusedContainerColor = Color.Transparent,
                        unfocusedContainerColor = Color.Transparent,
                        focusedIndicatorColor = Color.Transparent,
                        unfocusedIndicatorColor = Color.Transparent
                    ),
                    textStyle = TextStyle(
                        fontSize = 20.sp
                    ),
                    placeholder = {
                        Text(
                            text = "Password",
                            fontWeight = FontWeight(400))
                    },

                    singleLine = true,
                    visualTransformation = PasswordVisualTransformation(),
                    value = passwordTS,
                    onValueChange = {passwordTS = it },
                    keyboardOptions = KeyboardOptions(
                        keyboardType = KeyboardType.Text,
                        imeAction = ImeAction.Done
                    ),
                    keyboardActions = KeyboardActions(
                        onDone = {
                            if (studentIdTS == ""){
                                isStudentId = false
                                isPassword = true
                                isRegistered = true
                                if (passwordTS == ""){
                                    isPassword = false
                                }
                            } else if (passwordTS == ""){
                                isPassword = false
                                isStudentId = true
                                isRegistered = true
                            } else {
                                isPassword = true
                                isStudentId = true
                                isRegistered = false
                                loginViewModel.loginUser(studentIdTS, passwordTS)
                            }
                        }
                    ),
                )

                if (!isPassword){
                    Text(
                        text ="Password is required.",
                        modifier = Modifier
                            .offset(
                                (-101).dp, 5.dp),
                        style = TextStyle(
                            color = Color.Red,
                            fontSize = 13.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default,
                        ),
                    )
                }

                if (!isRegistered){
                    Text(
                        text ="Invalid Password.",
                        modifier = Modifier
                            .offset(
                                (-112).dp, 5.dp),
                        style = TextStyle(
                            color = Color.Red,
                            fontSize = 13.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default,
                        ),
                    )
                }

                Spacer(
                    modifier = Modifier
                        .height(if (!isPassword || !isRegistered) 22.dp else 27.dp)
                )

                Row{

                    Text(
                        text = "Don't have an account? ",
                        style = TextStyle(
                            color = Color.Black,
                            fontSize = 15.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default,
                        )
                    )

                    Text(
                        modifier = Modifier
                            .clickable {
                                navController.navigate(Pages.Sign_Up_Page1)
                            },
                        text = "Register!",
                        style = TextStyle(
                            color = Color(0xFF006400),
                            fontSize = 15.sp,
                            fontWeight = FontWeight(400),
                            fontFamily = FontFamily.Default
                        )
                    )
                }

                Spacer(
                    modifier = Modifier
                        .height(10.dp)
                )

                Text(
                    modifier = Modifier
                        .clickable {
                            navController.navigate(Pages.Forgot_Password_Page1)
                        },
                    text = "Forgot Password?",
                    style = TextStyle(
                        color = Color(0xFF006400),
                        fontSize = 15.sp,
                        fontWeight = FontWeight(400),
                        fontFamily = FontFamily.Default
                    )
                )

                Spacer(
                    modifier = Modifier
                        .height(60.dp)
                )

                // ------------------------------------------------------------ LOGIN BUTTON ------------------------------------------------------------
                Button(
                    onClick = {
                        if (studentIdTS == ""){
                            isStudentId = false
                            isPassword = true
                            isRegistered = true
                            if (passwordTS == ""){
                                isPassword = false
                            }
                        } else if (passwordTS == ""){
                            isPassword = false
                            isStudentId = true
                            isRegistered = true
                        } else {
                            isPassword = true
                            isStudentId = true
                            isRegistered = false
                            loginViewModel.loginUser(studentIdTS, passwordTS)
                        }
                    },
                    modifier = Modifier
                        .size(width = 290.dp, height = 43.dp),
                    shape = RoundedCornerShape(15.dp),
                    colors = ButtonDefaults.buttonColors(
                        containerColor = Color(0xFF72AF7B),
                        contentColor = Color.White
                    )
                ) {

                    Text(
                        text = "Login",
                        style = TextStyle(
                            color = Color.White,
                            fontSize = 16.sp,
                            fontWeight = FontWeight(600),
                            fontFamily = FontFamily.Default
                        )
                    )
                }
            }
        }
    }
}


class LoginViewModel(private val context: Context) : androidx.lifecycle.ViewModel() {

    private var loginStatus = MutableStateFlow("")

    private val _navigationEvent = MutableSharedFlow<String>() // Use SharedFlow
    val navigationEvent = _navigationEvent.asSharedFlow()


    fun loginUser(studentid: String, password: String) {
        val loginData = LoginData(studentid, password)
        val json = Gson().toJson(loginData)
        Log.d("Request Body", json)

        viewModelScope.launch {
            try {
                val response = RetrofitLogin.api.login(loginData)

                if (response.isSuccessful) {
                    val apiResponse = response.body()
                    val status = apiResponse?.status ?: "Unknown status"

                    loginStatus.value = status

                    if (status == "success") {
                        Toast.makeText(context, "Login successful", Toast.LENGTH_SHORT).show()
                        _navigationEvent.emit(Pages.Home_Page) // Emit navigation event
                    }

                    Log.d("Server Response", status)
                } else {
                    loginStatus.value = "Error: ${response.code()} - ${response.message()}"
                    Log.e("Server Error", "Code: ${response.code()}, Message: ${response.message()}")
                    Toast.makeText(context, "Invalid Account", Toast.LENGTH_LONG).show()
                }
            } catch (e: IOException) {
                loginStatus.value = "Network Error: ${e.message}"
                Log.e("Network Error", e.message.toString())
            } catch (e: Exception) {
                loginStatus.value = "Request failed: ${e.message}"
                Log.e("Request Error", e.message.toString())
            }
        }
    }
}


data class ApiResponseLogin(
    val status: String,
    val message: String? = null
)

data class LoginData(
    val studentid: String,
    val password: String
)

interface LoginServer {
    @POST("libTrack/login.php")
    suspend fun login(@Body loginData: LoginData): Response<ApiResponseLogin>
}

object RetrofitLogin {
<<<<<<< HEAD
    private const val BASE_URL = "http://10.40.98.57/" // IPV4 Address of the connection
=======
    private const val BASE_URL = "http://192.168.1.59/" // IPV4 Address of the connection
>>>>>>> parent of 8fb39ad (made some changes)

    val api: LoginServer by lazy {
        val gson = GsonBuilder().setLenient().create()
        val client = OkHttpClient.Builder().build()

        val retrofit = Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .client(client)
            .build()

        retrofit.create(LoginServer::class.java)
    }
}