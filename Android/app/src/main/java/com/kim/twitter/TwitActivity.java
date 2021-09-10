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
import android.view.View;
import android.view.ViewGroup;
import android.webkit.CookieManager;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class TwitActivity extends AppCompatActivity {

    // Cookie part
    CookieManager cookieManager = CookieManager.getInstance();
    String cookie = cookieManager.getCookie("http://10.0.2.2/");
    String[] cookieArray =  cookie.split(";");
    String[] cookieUid = cookieArray[0].split("=");
    String[] cookieTid = cookieArray[1].split("=");

    // Declaration part
    json task;
    TextView textUsername, textPosttime, textBody, textThumb, textComment;
    Button buttonFollow;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_twit);

        textUsername = (TextView)findViewById(R.id.textUsername);
        textPosttime = (TextView)findViewById(R.id.textPosttime);
        textBody = (TextView)findViewById(R.id.textBody);
        textThumb = (TextView)findViewById(R.id.textThumb);
        textComment = (TextView)findViewById(R.id.textComment);
        buttonFollow = (Button)findViewById(R.id.buttonFollow);

        // Connect localhost (http://10.0.2.2/)
        task = new json();
        task.execute("http://10.0.2.2/twit.php");
    }

    private class json extends AsyncTask<String,Integer,String> {
        @Override
        protected String doInBackground(String... urls) {
            StringBuilder jsonHtml = new StringBuilder();
            try {
                String data = URLEncoder.encode("uid", "UTF-8") + "=" + URLEncoder.encode(cookieUid[1], "UTF-8");
                data += "&" + URLEncoder.encode("tid", "UTF-8") + "=" + URLEncoder.encode(cookieTid[1], "UTF-8");
                URL url = new URL(urls[0]);
                HttpURLConnection connection = (HttpURLConnection)url.openConnection();

                if(connection != null) {
                    connection.setConnectTimeout(10000);
                    connection.setDoOutput(true);
                    connection.setUseCaches(false);

                    OutputStreamWriter osw = new OutputStreamWriter(connection.getOutputStream());
                    osw.write(data);
                    osw.flush();

                    if(connection.getResponseCode() == HttpURLConnection.HTTP_OK) {
                        BufferedReader br = new BufferedReader(new InputStreamReader(connection.getInputStream(), "UTF-8"));
                        for(;;) {
                            String line = br.readLine();
                            if(line == null) break;
                            jsonHtml.append(line + "\n");
                        }
                        br.close();
                    }
                    connection.disconnect();
                }
            } catch(Exception e) {
                e.printStackTrace();
            }
            return jsonHtml.toString();
        }

        protected void onPostExecute(String s) {
            parser(s);
        }

        protected void parser(String s) {
            StringBuffer sb = new StringBuffer();
            try {
                JSONArray jarray = new JSONArray(s);
                for(int i=0; i < jarray.length(); i++) {
                    JSONObject jObject = jarray.getJSONObject(i);
                    String writer = jObject.getString("writer");
                    String posttime = jObject.getString("posttime");
                    String body = jObject.getString("body");
                    String thumb = jObject.getString("thumb");
                    String comment = jObject.getString("comment");
                    int follow = jObject.getInt("follow");
                    textUsername.setText(writer);
                    textPosttime.setText(posttime);
                    textBody.setText(body);
                    textThumb.setText(thumb);
                    textComment.setText(comment);
                    if(follow == 0) {
                        buttonFollow.setText("Follow");
                        buttonFollow.setBackgroundColor(Color.parseColor("#FF5A97FF"));
                        buttonFollow.setVisibility(View.VISIBLE);
                    } else if(follow == 1) {
                        buttonFollow.setText("Following");
                        buttonFollow.setBackgroundColor(Color.parseColor("#FF4CAF50"));
                        buttonFollow.setVisibility(View.VISIBLE);
                    } else {
                        buttonFollow.setVisibility(View.GONE);
                    }
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }

    // If click [FOLLOW] or [FOLLOWING] button
    public void SubmitForm(View view) {
        ViewGroup layout = (ViewGroup) buttonFollow.getParent();
        layout.removeView(buttonFollow);

        WebView webview = new WebView(this);
        setContentView(webview);
        String url = "http://10.0.2.2/follow.php";
        String postData = "uid=" + cookieUid[1] + "&tid=" + cookieTid[1];
        webview.postUrl(url,postData.getBytes());
        finish();
    }
}