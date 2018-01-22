package io.laterain.stocksearch.Fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebSettings;
import android.webkit.WebView;

import io.laterain.stocksearch.Activities.StockContentActivity;
import io.laterain.stocksearch.R;

public class HistoricalChartFragment extends android.support.v4.app.Fragment {

    public String symbol;

    private WebView historicalChartWebView;

    public HistoricalChartFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        StockContentActivity parentActivity = (StockContentActivity) getActivity();
        this.symbol = parentActivity.symbol;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View rootView = inflater.inflate(R.layout.fragment_historical_chart, container, false);
        historicalChartWebView = (WebView) rootView.findViewById(R.id.historicalChartWebView);
        loadHistoricalChart();
        return rootView;
    }

    private void loadHistoricalChart() {
        String url = getString(R.string.url_get_stock_historical_chart) + symbol;
        System.out.println(url);
        WebSettings webSettings = historicalChartWebView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        historicalChartWebView.loadUrl(url);
    }
}
