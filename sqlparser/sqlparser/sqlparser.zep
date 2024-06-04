namespace Sqlparser;


%{
extern const char* sql2json(const char*, const char*);
}%

class Sqlparser{

	public static function sql2json(string sql, string dialog = "generic"){
        var _json = sql2json(sql, dialog);
        return _json;
	}
}