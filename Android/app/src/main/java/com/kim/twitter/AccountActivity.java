/*
  COMP.3100 Database II (Spring 2018)
  Project: Design and Implementation of a Web-based Database System
  Author: Junghwan Kim (Junghwan_Kim@student.uml.edu), Rotana Nou(Rotana_Nou@student.uml.edu)
*/

package com.kim.twitter;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.webkit.CookieManager;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.Toast;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;

import android.os.AsyncTask;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class AccountActivity extends AppCompatActivity {

    // Cookie part
    CookieManager cookieManager = CookieManager.getInstance();
    String cookie = cookieManager.getCookie("http://10.0.2.2/");
    String[] cookieArray =  cookie.split(";");
    String[] cookieUid = cookieArray[0].split("=");

    // Declaration part
    EditText editTextBody;
    TextView txtUsername, txtEmail, txtThumb, txtComment;
    json task;

    private static String TAG = "json";
    private static final String TAG_JSON="twitts";
    private static final String TAG_TID = "tid";
    private static final String TAG_USERNAME = "username";
    private static final String TAG_POSTTIME ="posttime";
    private static final String TAG_BODY ="body";
    private static final String TAG_THUMB ="thumb";
    private static final String TAG_COMMENT ="comment";
    ArrayList<HashMap<String, String>> mArrayList;
    ListView mlistView;
    String mJsonString;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_account);

        txtUsername = (TextView) findViewById(R.id.textUsername);
        txtEmail = (TextView) findViewById(R.id.textEmail);
        txtThumb = (TextView) findViewById(R.id.textThumb);
        txtComment = (TextView) findViewById(R.id.textComment);
        editTextBody = (EditText) findViewById(R.id.editBody);

        task = new json();
        task.execute("http://10.0.2.2/account.php");

        mlistView = (ListView) findViewById(R.id.listView_main_list);
        mArrayList = new ArrayList<>();

        GetData task2 = new GetData();
        task2.execute("http://10.0.2.2/twitts.php");
    }

    // If click [POST] button
    public void SubmitForm(View view) {
        String body = editTextBody.getText().toString();
        RunDatabase(body);
    }

    private void RunDatabase(String body) {
        class InsertData extends AsyncTask<String, Void, String> {
            ProgressDialog loading;
            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                loading = ProgressDialog.show(AccountActivity.this, "Please Wait", null, true, true);
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
                    String body = (String) params[0];

                    // Connect localhost (http://10.0.2.2/)
                    String link = "http://10.0.2.2/post.php";
                    String data = URLEncoder.encode("uid", "UTF-8") + "=" + URLEncoder.encode(cookieUid[1], "UTF-8");
                    data += "&" + URLEncoder.encode("body", "UTF-8") + "=" + URLEncoder.encode(body, "UTF-8");

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

                    if (line.equalsIgnoreCase("Your twit has been posted now.")) {
                        Intent intent = getIntent();
                        overridePendingTransition(0, 0);
                        intent.addFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
                        finish();
                        overridePendingTransition(0, 0);
                        startActivity(intent);
                        sb.append(line);
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
        task.execute(body);
    }

    // Menu part
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.activity_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        //return super.onOptionsItemSelected(item);
        switch (item.getItemId()) {
            case R.id.menu1:
                startActivity((new Intent(AccountActivity.this, RankActivity.class)));
                return true;
            default:
                cookieManager.removeSessionCookie();
                Toast.makeText(getApplicationContext(), "Logout!", Toast.LENGTH_LONG).show();
                finish();
                return super.onOptionsItemSelected(item);
        }
    }

    // Get account information
    private class json extends AsyncTask<String, Integer,String> {
        @Override
        protected String doInBackground(String... urls) {
            StringBuilder jsonHtml = new StringBuilder();
            try{
                String data = URLEncoder.encode("uid", "UTF-8") + "=" + URLEncoder.encode(cookieUid[1], "UTF-8");
                URL url = new URL(urls[0]);
                HttpURLConnection conn = (HttpURLConnection)url.openConnection();
                if(conn != null){
                    conn.setConnectTimeout(10000);
                    conn.setDoOutput(true);
                    conn.setUseCaches(false);
                    OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
                    wr.write(data);
                    wr.flush();
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
            parser(str);
        }

        protected void parser(String str) {
            StringBuffer sb = new StringBuffer();
            try {
                JSONArray jarray = new JSONArray(str);
                for(int i=0; i < jarray.length(); i++){
                    JSONObject jObject = jarray.getJSONObject(i);
                    String username = jObject.getString("username");
                    String email = jObject.getString("email");
                    txtUsername.setText(username);
                    txtEmail.setText(email);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }

    // Get twitts data
    private class GetData extends AsyncTask<String, Void, String>{
        String errorString = null;

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            mJsonString = result;
            showResult();
        }

        @Override
        protected String doInBackground(String... params) {
            String serverURL = params[0];
            try {
                String data = URLEncoder.encode("uid", "UTF-8") + "=" + URLEncoder.encode(cookieUid[1], "UTF-8");
                URL url = new URL(serverURL);
                HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                httpURLConnection.setReadTimeout(5000);
                httpURLConnection.setConnectTimeout(5000);
                httpURLConnection.setDoOutput(true);
                httpURLConnection.connect();

                OutputStreamWriter wr = new OutputStreamWriter(httpURLConnection.getOutputStream());
                wr.write(data);
                wr.flush();

                int responseStatusCode = httpURLConnection.getResponseCode();
                Log.d(TAG, "response code - " + responseStatusCode);

                InputStream inputStream;
                if(responseStatusCode == HttpURLConnection.HTTP_OK) {
                    inputStream = httpURLConnection.getInputStream();
                }
                else{
                    inputStream = httpURLConnection.getErrorStream();
                }

                InputStreamReader inputStreamReader = new InputStreamReader(inputStream, "UTF-8");
                BufferedReader bufferedReader = new BufferedReader(inputStreamReader);

                StringBuilder sb = new StringBuilder();
                String line;

                while((line = bufferedReader.readLine()) != null){
                    sb.append(line);
                }

                bufferedReader.close();
                return sb.toString().trim();
            } catch (Exception e) {
                Log.d(TAG, "InsertData: Error ", e);
                errorString = e.toString();
                return null;
            }
        }
    }

    // Show twitts list
    private void showResult(){
        try {
            JSONObject jsonObject = new JSONObject(mJsonString);
            JSONArray jsonArray = jsonObject.getJSONArray(TAG_JSON);

            for(int i=0;i<jsonArray.length();i++){
                JSONObject item = jsonArray.getJSONObject(i);

                String tid = item.getString(TAG_TID);
                String username = item.getString(TAG_USERNAME);
                String posttime = item.getString(TAG_POSTTIME);
                String body = item.getString(TAG_BODY);
                String thumb = item.getString(TAG_THUMB);
                String comment = item.getString(TAG_COMMENT);

                HashMap<String,String> hashMap = new HashMap<>();

                hashMap.put(TAG_TID, tid);
                hashMap.put(TAG_USERNAME, username);
                hashMap.put(TAG_POSTTIME, posttime);
                hashMap.put(TAG_BODY, body);
                hashMap.put(TAG_THUMB, thumb);
                hashMap.put(TAG_COMMENT, comment);

                mArrayList.add(hashMap);
            }

            ListAdapter adapter = new SimpleAdapter(
                   AccountActivity.this, mArrayList, R.layout.listview_twitts,
                    new String[]{TAG_TID,TAG_USERNAME,TAG_POSTTIME,TAG_BODY,TAG_THUMB, TAG_COMMENT},
                    new int[]{R.id.textTid, R.id.textUsername, R.id.textPosttime, R.id.textBody, R.id.textThumb, R.id.textComment}
            );

            mlistView.setAdapter(adapter);
            mlistView.setClickable(true);
            AdapterView.OnItemClickListener listener = new AdapterView.OnItemClickListener() {
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                   String tid = ((TextView) view.findViewById(R.id.textTid)).getText().toString();
                   cookieManager.setCookie("http://10.0.2.2/", "tid=''");
                   cookieManager.setCookie("http://10.0.2.2/", "tid=" + tid);
                   startActivity((new Intent(AccountActivity.this, TwitActivity.class)));
                }
            };
            mlistView.setOnItemClickListener(listener);
        } catch (JSONException e) {
            Log.d(TAG, "showResult : ", e);
        }
    }
}