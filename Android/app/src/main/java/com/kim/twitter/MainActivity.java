/*
  COMP.3100 Database II (Spring 2018)
  Project: Design and Implementation of a Web-based Database System
  Author: Junghwan Kim (Junghwan_Kim@student.uml.edu), Rotana Nou(Rotana_Nou@student.uml.edu)
*/

package com.kim.twitter;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.content.Intent;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import android.webkit.CookieManager;

public class MainActivity extends AppCompatActivity {

    // Cookie part
    CookieManager cookieManager = CookieManager.getInstance();

    // Declaration part
    private EditText editTextUsername;
    private EditText editTextPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        editTextUsername = (EditText) findViewById(R.id.username);
        editTextPassword = (EditText) findViewById(R.id.password);
    }

    // If click [SIGN UP] button
    public void ClickSignup(View view) {
        Intent intent = new Intent(this, SignupActivity.class);
        startActivity(intent);
    }

    // If click [LOGIN] button
    public void SubmitForm(View view) {
        String username = editTextUsername.getText().toString();
        String password = editTextPassword.getText().toString();
        //String aid = Settings.Secure.getString(MainActivity.this.getContentResolver(), Settings.Secure.ANDROID_ID);
        RunDatabase(username, password);
    }

    private void RunDatabase(String username, String password) {
        class InsertData extends AsyncTask<String, Void, String> {
            ProgressDialog loading;
            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(MainActivity.this, "Please Wait", null, true, true);
            }
            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                loading.dismiss();
                Toast.makeText(getApplicationContext(), s, Toast.LENGTH_LONG).show();
            }
            @Override
            protected String doInBackground(String... params) {
                try {
                    String username = (String) params[0];
                    String password = (String) params[1];

                    // Connect localhost (http://10.0.2.2/)
                    String link = "http://10.0.2.2/signin.php";
                    String data = URLEncoder.encode("username", "UTF-8") + "=" + URLEncoder.encode(username, "UTF-8");
                    data += "&" + URLEncoder.encode("password", "UTF-8") + "=" + URLEncoder.encode(password, "UTF-8");

                    URL url = new URL(link);
                    URLConnection conn = url.openConnection();

                    conn.setDoOutput(true);
                    OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());

                    wr.write(data);
                    wr.flush();

                    BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));

                    StringBuilder sb = new StringBuilder();
                    String line = null;

                    // Read Server Response
                    line = reader.readLine();

                    if(isNumeric(line)) {
                        sb.append("Welcome!");
                        cookieManager.removeSessionCookie();
                        cookieManager.setCookie("http://10.0.2.2/", "uid=" + line);
                        startActivity((new Intent(MainActivity.this, AccountActivity.class)));
                    } else {
                        sb.append(line);
                    }
                    return sb.toString();
                } catch (Exception e) {
                    return new String("Exception: " + e.getMessage());
                }
            }
        }
        InsertData task = new InsertData();
        task.execute(username, password);
    }

    // isNumber
    public static boolean isNumeric(String str) {
        return str.matches("-?\\d+(\\.\\d+)?");  //match a number with optional '-' and decimal.
    }
}