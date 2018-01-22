package io.laterain.stocksearch.Adapters;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.Set;
import java.util.TreeSet;

import io.laterain.stocksearch.R;

public class FavoriteStockCellAdapter extends BaseAdapter {

    private ArrayList<String> mFavoriteStockCellSymbols;
    private ArrayList<String> mFavoriteStockCellPrices;
    private ArrayList<String> mFavoriteStockCellChanges;

    private static LayoutInflater inflater;

    public FavoriteStockCellAdapter(Context context,
                                    ArrayList<String> favoriteListSymbols,
                                    ArrayList<String> favoriteListPrices,
                                    ArrayList<String> favoriteListChanges) {
        this.inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mFavoriteStockCellSymbols = favoriteListSymbols;
        this.mFavoriteStockCellPrices = favoriteListPrices;
        this.mFavoriteStockCellChanges = favoriteListChanges;
    }

    @Override
    public int getCount() {
        return mFavoriteStockCellSymbols.size();
    }

    @Override
    public Object getItem(int position) {
        return mFavoriteStockCellSymbols.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    static class StockNewsCellHolder {
        TextView favoriteStockCellSymbol;
        TextView favoriteStockCellPrice;
        TextView favoriteStockCellChange;
    }

    @Override
    public View getView (final int position, View convertView, ViewGroup parent) {
        StockNewsCellHolder holder;

        if (convertView == null) {
            holder = new StockNewsCellHolder();
            convertView = inflater.inflate(R.layout.list_cell_favorite_stock, parent, false);
            holder.favoriteStockCellSymbol = (TextView) convertView.findViewById(R.id.favoriteStockCellSymbol);
            holder.favoriteStockCellPrice = (TextView) convertView.findViewById(R.id.favoriteStockCellPrice);
            holder.favoriteStockCellChange = (TextView) convertView.findViewById(R.id.favoriteStockCellChange);
            convertView.setTag(holder);
        } else {
            holder = (StockNewsCellHolder) convertView.getTag();
        }

        holder.favoriteStockCellSymbol.setText(mFavoriteStockCellSymbols.get(position));
        holder.favoriteStockCellPrice.setText(mFavoriteStockCellPrices.get(position));
        String change = mFavoriteStockCellChanges.get(position);
        holder.favoriteStockCellChange.setText(change);
        if (change.startsWith("-")) {
            holder.favoriteStockCellChange.setTextColor(Color.parseColor("#D32F2F"));
        } else if (change.startsWith("0.00 (") || change.startsWith("0 (")){
            holder.favoriteStockCellChange.setBackgroundColor(Color.TRANSPARENT);
            holder.favoriteStockCellChange.setTextColor(Color.BLACK);
        } else {
            holder.favoriteStockCellChange.setTextColor(Color.parseColor("#43A047"));
        }

        return convertView;
    }

    public void refreshData (ArrayList<String> newPrices,
                             ArrayList<String> newChanges) {
        this.mFavoriteStockCellPrices = newPrices;
        this.mFavoriteStockCellChanges = newChanges;
        notifyDataSetChanged();
    }
}
