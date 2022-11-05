package com.example.myapplication

import android.os.Bundle
import android.os.StrictMode
import android.view.Menu
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.drawerlayout.widget.DrawerLayout
import androidx.navigation.findNavController
import androidx.navigation.ui.AppBarConfiguration
import androidx.navigation.ui.navigateUp
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import com.example.myapplication.databinding.ActivityMainBinding
import com.google.android.material.navigation.NavigationView
import com.google.android.material.snackbar.Snackbar
import oracle.jdbc.driver.OracleDriver
import oracle.jdbc.pool.OracleDataSource
import java.sql.Connection
import java.sql.DriverManager


class MainActivity : AppCompatActivity() {

    private lateinit var appBarConfiguration: AppBarConfiguration
    private lateinit var binding: ActivityMainBinding

    // oracle databaze config
    private val DRIVER = "oracle.jdbc.driver.OracleDriver"
    private val URL = "jdbc:oracle:thin:@fei-sql1.upceucebny.cz:1521:IDAS " // TODO: něco je asi špatňe :(
    private val USERNAME = "st64116"
    private val PASSWORD = "cislo123"
    private lateinit var connection: Connection

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setSupportActionBar(binding.appBarMain.toolbar)

        binding.appBarMain.fab.setOnClickListener { view ->
            Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                .setAction("Action", null).show()
        }
        val drawerLayout: DrawerLayout = binding.drawerLayout
        val navView: NavigationView = binding.navView
        val navController = findNavController(R.id.nav_host_fragment_content_main)
        // Passing each menu ID as a set of Ids because each
        // menu should be considered as top level destinations.
        appBarConfiguration = AppBarConfiguration(
            setOf(
                R.id.nav_home, R.id.nav_gallery, R.id.nav_slideshow
            ), drawerLayout
        )
        setupActionBarWithNavController(navController, appBarConfiguration)
        navView.setupWithNavController(navController)


        //oracle connection
        val threadPolicy = StrictMode.ThreadPolicy.Builder().permitAll().build()
        StrictMode.setThreadPolicy(threadPolicy)

        try {

            try {
                Class.forName(DRIVER)
            } catch (e: java.lang.Exception) {
                println(e)
            }

//            val ods = OracleDataSource()
//            ods.url = connString
//            ods.user = "scott"
//            ods.setPassword("tiger")
//            val conn = ods.getConnection()


//            val driver = OracleDriver();
//            DriverManager.registerDriver(driver)
            println("connecting...");
            this.connection = DriverManager.getConnection(URL, USERNAME, PASSWORD)
            println("connected!")
            Toast.makeText(this, "CONNECTED", Toast.LENGTH_LONG)
            val statement = connection.createStatement()
            val resultSet = statement.executeQuery("select * from pojistky")

            while (resultSet.next()) {
                println(resultSet.getString(0))
            }
            println("nvm")
            connection.close()
        } catch (e: Exception) {
            println("===exception===");
            println(e)
            println("===exception===");
        }
        //oracle connection
    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        // Inflate the menu; this adds items to the action bar if it is present.
        menuInflater.inflate(R.menu.main, menu)
        return true
    }

    override fun onSupportNavigateUp(): Boolean {
        val navController = findNavController(R.id.nav_host_fragment_content_main)
        return navController.navigateUp(appBarConfiguration) || super.onSupportNavigateUp()
    }
}