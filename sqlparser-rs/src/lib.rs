extern crate libc;

//使用C类型约束
use std::ffi::{CStr, CString};
use std::str::FromStr;

use libc::{c_char};

use sqlparser::dialect::dialect_from_str;
use sqlparser::parser::Parser;


#[no_mangle]
pub extern "C" fn sql2json(sql:*const c_char, dialect:*const c_char) -> *mut c_char {
    let input_sql = unsafe{CStr::from_ptr(sql).to_str().unwrap()};
    let input_dialect = unsafe{CStr::from_ptr(dialect).to_str().unwrap()};

    let dialect = dialect_from_str(input_dialect.to_string()).unwrap();
    let parse_result = Parser::parse_sql(&*dialect, input_sql);
    match parse_result {
        Ok(statements) => {
            let serialized = serde_json::to_string_pretty(&statements).unwrap();

            //String 转 C CString
        
            let a = CString::new(serialized.as_str()).unwrap();
        
            //C CString 转 C char
        
            //这里实属无奈，因为rust ffi中阐述，对字符串返回只能是该字符串地址，所以需要该方法进行返回C才能接收到！
        
            let r = a.into_raw();
            return r;
        }
        Err(e) => {
            let n = String::from_str("").unwrap();

            //String 转 C CString
        
            let a = CString::new(n.as_str()).unwrap();
        
            //C CString 转 C char
        
            //这里实属无奈，因为rust ffi中阐述，对字符串返回只能是该字符串地址，所以需要该方法进行返回C才能接收到！
        
            let r = a.into_raw();
            return r;
        }
    }
}