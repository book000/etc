package com.jaoafa.MyMaid2.Lib;

import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.bukkit.entity.EntityType;

/**
 * killコマンド等で可変するプレイヤーを指定するための仕組み、セレクターを判定するクラス<br>
 * Minecraft Version 1.12.2対応
 * @author mine_book000
 */
public class ParseSelector {
	/*
	 * @p	最寄りのプレイヤー1人
	 * @r	ランダムなプレイヤー
	 * @a	全てのプレイヤー
	 * @e	全てのエンティティ
	 * @s	コマンドを実行しているエンティティ
	 */
	List<String> selector_list = Arrays.asList("p", "r", "a", "e", "s");
	/**
	 * x, y, z	座標
	 * r, rm	半径（最大、最小）
	 * dx, dy, dz	範囲の大きさ
	 *
	 * score_name	最大スコア (非対応)
	 * score_name_min	最小スコア (非対応)
	 * tag	スコアボードのタグ
	 * team	チーム名
	 *
	 * c	数
	 * l, lm	経験値レベル（最大、最小）
	 * m	ゲームモード
	 * name	エンティティ名
	 * rx, rxm	X 軸を中心とした向き（最大、最小）
	 * ry, rym	Y 軸を中心とした向き （最大、最小）
	 * type	エンティティの種類
	 */
	List<String> argument_list = Arrays.asList(
			"x", "y", "z",
			"r", "rm",
			"dx", "dy", "dz",
			"tag",
			"team",
			"c",
			"l", "lm",
			"m",
			"name",
			"rx", "rxm",
			"ry", "rym",
			"type"
			);


	boolean valid = true;
	String selector;
	Map<String, String> args = new HashMap<String, String>();
	/**
	 * ParseSelectorクラスの作成
	 * @param SelectorText セレクター
	 * @return ParseSelectorクラス
	 * @throws IllegalArgumentException 指定されたセレクターが適切でなかった場合に発生します。!
	 * @author mine_book000
	 */
	public ParseSelector(String SelectorText) throws IllegalArgumentException{
		Pattern p = Pattern.compile("^@(.)(.*)$");
		Matcher m = p.matcher(SelectorText);
		if(!m.find()){
			valid = false;
			throw new IllegalArgumentException("セレクターテキストがセレクターとして認識できません。");
		}

		selector = m.group(1);
		if(!selector_list.contains(selector)){
			throw new IllegalArgumentException("セレクターが認識できません。");
		}

		if(m.group(2).equals("[]")){
			throw new IllegalArgumentException("セレクターの引数が認識できません。");
		}

		p = Pattern.compile("^\\[(.+)\\]$");
		m = p.matcher(m.group(2));
		if(!m.find()){
			return;
		}

		if(m.group(1).equals("")){
			throw new IllegalArgumentException("セレクターの引数が認識できません。");
		}

		if(!m.group(1).contains(",")){
			String arg = m.group(1);
			if(arg.contains("=")){
				String[] key_value = arg.split("=");
				String key = key_value[0];
				String value = key_value[1];
				this.args.put(key, value);
			}else{
				throw new IllegalArgumentException("セレクターの1番目の引数が認識できません。");
			}
			return;
		}

		String[] args = m.group(1).split(",");
		int i = 0;
		for(String arg : args){
			if(arg.contains("=")){
				String[] key_value = arg.split("=");
				String key = key_value[0];
				String value = key_value[1];
				this.args.put(key, value);
				i++;
			}else{
				throw new IllegalArgumentException("セレクターの" + (i + 1) +"番目の引数が認識できません。");
			}
		}
	}
	/**
	 * StringがInt型かを判定する
	 * @param num 判定するString
	 * @return Int型ならtrue,そうでなければfalse
	 */
	private boolean isNumber(String num) {
		try {
			Integer.parseInt(num);
			return true;
		} catch (NumberFormatException e) {
			return false;
		}
	}

	/**
	 * 引数が適当であるかを調べる
	 * @return 引数が適当であればtrue
	 */
	@SuppressWarnings("deprecation")
	public boolean isValidValues(){
		if(valid == false){
			return false;
		}
		if(args.containsKey("x")){
			if(!isNumber(args.get("x"))){
				return false;
			}
		}
		if(args.containsKey("y")){
			if(!isNumber(args.get("y"))){
				return false;
			}
		}
		if(args.containsKey("z")){
			if(!isNumber(args.get("z"))){
				return false;
			}
		}
		if(args.containsKey("r")){
			if(!isNumber(args.get("r"))){
				return false;
			}
		}
		if(args.containsKey("type")){
			for(EntityType type : EntityType.values()){
				if(!"Player".equals(args.get("type"))){
					if(!type.getName().equalsIgnoreCase(args.get("type"))){
						return false;
					}
				}
			}
		}
		return true;
	}
	/**
	 * セレクターを取得する(@付き)
	 * @return セレクター
	 */
	public String getSelector(){
		return "@" + selector;
	}
	/**
	 * セレクター引数を取得する
	 * @return セレクター引数
	 */
	public Map<String, String> getArgs(){
		return args;
	}
}
