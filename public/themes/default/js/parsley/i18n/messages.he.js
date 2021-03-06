window.ParsleyConfig = window.ParsleyConfig || {};

(function ($) {
  window.ParsleyConfig = $.extend( true, {}, window.ParsleyConfig, {
    messages: {
      // parsley //////////////////////////////////////
        defaultMessage: "הערך הזה אינו תקין."
        , type: {
            email:      "ערך זה צריך להכיל כתובת דואר אלקטרוני תקינה."
          , url:        "ערך זה צריך להיות קישור תקין."
          , urlstrict:  "ערך זה צריך להיות קישור תקין."
          , number:     "ערך זה צריך להכיל מספר תקין."
          , digits:     "ערך זה צריך להכיל רק ספרות."
          , dateIso:    "ערך זה צריך להיות תאריך תקין."
          , alphanum:   "ערך זה צריך להכיל ספרות ו/או ספרות בלבד."
          , phone:      "ערך זה צריך להכיל מספר טלפון תקין."
        }
      , notnull:        "ערך זה לא יכול להיות ריק."
      , notblank:       "ערך זה לא יכול להיות ריק."
      , required:       "עליך למלא ערך זה."
      , regexp:         "ערך זה אינו תקין."
      , min:            "ערך זה צריך להיות גדול או שווה ל-%s."
      , max:            "ערך זה צריך להיות קטן מ-%s או שווה לו."
      , range:          "ערך זה צריך להיות בין %s עד %s."
      , minlength:      "ערך זה צריך להכיל לפחות %s תווים."
      , maxlength:      "ערך זה צריך להכיל מקסימום %s תווים."
      , rangelength:    "ערך זה צריך להכיל בין %s עד %s תווים."
      , mincheck:       "עליך לבחור לפחות %s אפשרויות."
      , maxcheck:       "עליך לבחור %s אפשרויות או פחות."
      , rangecheck:     "עליך לבחור בין %s עד %s אפשרויות."
      , equalto:        "ערך זה צריך להיות זהה."

      // parsley.extend ///////////////////////////////
      , minwords:       "ערך זה צריך להכיל לפחות %s מילים."
      , maxwords:       "ערך זה צריך להכיל מקסימום %s מילים."
      , rangewords:     "ערך זה צריך להכיל בין %s עד %s מילים."
      , greaterthan:    "ערך זה צריך להיות גדול מ-%s."
      , lessthan:       "ערך זה צריך להיות קטן מ-%s."
      , beforedate:     "תאריך זה צריך להיות לפני %s."
      , afterdate:      "תאריך זה צריך להיות אחרי %s."
      , americandate:	"ערך זה צריך להיות תאריך תקין."
    }
  });
}(window.jQuery || window.Zepto));