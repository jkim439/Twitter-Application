/*
  COMP.3100 Database II (Spring 2018)
  Project: Design and Implementation of a Web-based Database System
  Author: Junghwan Kim (Junghwan_Kim@student.uml.edu), Rotana Nou(Rotana_Nou@student.uml.edu)
*/

package com.kim.twitter;

import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class RankActivity extends AppCompatActivity {

    // Declaration part
    json task;
    TextView txtfollowUsername, txtfollowNum, txtthumbUsername, txtthumbPosttime, txtthumbBody, txtthumbNum;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setTitle("Top Rank");
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_rank);

        txtfollowUsername = (TextView)findViewById(R.id.textfollowUsername);
        txtfollowNum = (TextView)findViewById(R.id.textfollowNum);
        txtthumbUsername = (TextView)findViewById(R.id.textUsername);
        txtthumbPosttime = (TextView)findViewById(R.id.textthumbPosttime);
        txtthumbBody = (TextView)findViewById(R.id.textBody);
        txtthumbNum = (TextView)findViewById(R.id.textthumbNum);

        // Connect localhost (http://10.0.2.2/)
        task = new json();
        task.execute("http://10.0.2.2/rank.php");
    }

    // Get top rank data
    private class json extends AsyncTask<String, Integer,String> {
        @Override
        protected String doInBackground(String... urls) {
            StringBuilder jsonHtml = new StringBuilder();
            try{
                URL url = new URL(urls[0]);
                HttpURLConnection conn = (HttpURLConnection)url.openConnection();
                if(conn != null){
                    conn.setConnectTimeout(10000);
                    conn.setDoOutput(true);
                    conn.setUseCaches(false);
                    if(conn.getResponseCode() == HttpURLConnection.HTTP_OK){
                        BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"));
                        for(;;){
                            String line = br.readLine();
                            if(line == null) break;
                            jsonHtml.append(line + "\n");
                        }
                        br.close();
                    }
                    conn.disconnect();
                }
            } catch(Exception ex){
                ex.printStackTrace();
            }
            return jsonHtml.toString();
        }

        protected void onPostExecute(String str) {
            txtthumbNum.setTextColor(Color.RED);
            parser(str);
        }

        // Show top rank data
        protected void parser(String str) {
            StringBuffer sb = new StringBuffer();
            try {
                JSONArray jarray = new JSONArray(str);
                for(int i=0; i < jarray.length(); i++){
                    JSONObject jObject = jarray.getJSONObject(i);
                    String followUsername = jObject.getString("followUsername");
                    String followNum = jObject.getString("followNum");
                    String thumbUsername = jObject.getString("thumbUsername");
                    String thumbPosttime = jObject.getString("thumbPosttime");
                    String thumbBody = jObject.getString("thumbBody");
                    String thumbNum = jObject.getString("thumbNum");
                    txtfollowUsername.setText(followUsername);
                    txtfollowNum.setText(followNum);
                    txtthumbUsername.setText(thumbUsername);
                    txtthumbPosttime.setText(thumbPosttime);
                    txtthumbBody.setText(thumbBody);
                    txtthumbNum.setText(thumbNum);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }
}