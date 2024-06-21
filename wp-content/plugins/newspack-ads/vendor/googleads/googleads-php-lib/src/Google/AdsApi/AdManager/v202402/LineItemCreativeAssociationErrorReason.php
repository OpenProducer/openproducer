<?php

namespace Google\AdsApi\AdManager\v202402;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class LineItemCreativeAssociationErrorReason
{
    const CREATIVE_IN_WRONG_ADVERTISERS_LIBRARY = 'CREATIVE_IN_WRONG_ADVERTISERS_LIBRARY';
    const INVALID_LINEITEM_CREATIVE_PAIRING = 'INVALID_LINEITEM_CREATIVE_PAIRING';
    const STARTDATE_BEFORE_LINEITEM_STARTDATE = 'STARTDATE_BEFORE_LINEITEM_STARTDATE';
    const STARTDATE_NOT_BEFORE_LINEITEM_ENDDATE = 'STARTDATE_NOT_BEFORE_LINEITEM_ENDDATE';
    const ENDDATE_AFTER_LINEITEM_ENDDATE = 'ENDDATE_AFTER_LINEITEM_ENDDATE';
    const ENDDATE_NOT_AFTER_LINEITEM_STARTDATE = 'ENDDATE_NOT_AFTER_LINEITEM_STARTDATE';
    const ENDDATE_NOT_AFTER_STARTDATE = 'ENDDATE_NOT_AFTER_STARTDATE';
    const ENDDATE_IN_THE_PAST = 'ENDDATE_IN_THE_PAST';
    const CANNOT_COPY_WITHIN_SAME_LINE_ITEM = 'CANNOT_COPY_WITHIN_SAME_LINE_ITEM';
    const UNSUPPORTED_CREATIVE_VAST_REDIRECT_TYPE = 'UNSUPPORTED_CREATIVE_VAST_REDIRECT_TYPE';
    const UNSUPPORTED_YOUTUBE_HOSTED_CREATIVE = 'UNSUPPORTED_YOUTUBE_HOSTED_CREATIVE';
    const PROGRAMMATIC_CREATIVES_CAN_ONLY_BE_ASSIGNED_TO_ONE_LINE_ITEM = 'PROGRAMMATIC_CREATIVES_CAN_ONLY_BE_ASSIGNED_TO_ONE_LINE_ITEM';
    const CANNOT_ACTIVATE_ASSOCIATION_WITH_INACTIVE_CREATIVE = 'CANNOT_ACTIVATE_ASSOCIATION_WITH_INACTIVE_CREATIVE';
    const CANNOT_CREATE_PROGRAMMATIC_CREATIVES = 'CANNOT_CREATE_PROGRAMMATIC_CREATIVES';
    const CANNOT_UPDATE_PROGRAMMATIC_CREATIVES = 'CANNOT_UPDATE_PROGRAMMATIC_CREATIVES';
    const CREATIVE_AND_LINE_ITEM_MUST_BOTH_BE_SET_TOP_BOX_ENABLED = 'CREATIVE_AND_LINE_ITEM_MUST_BOTH_BE_SET_TOP_BOX_ENABLED';
    const CANNOT_DELETE_SET_TOP_BOX_ENABLED_ASSOCIATIONS = 'CANNOT_DELETE_SET_TOP_BOX_ENABLED_ASSOCIATIONS';
    const SET_TOP_BOX_CREATIVE_ROTATION_WEIGHT_MUST_BE_INTEGER = 'SET_TOP_BOX_CREATIVE_ROTATION_WEIGHT_MUST_BE_INTEGER';
    const INVALID_CREATIVE_ROTATION_TYPE_FOR_MANUAL_WEIGHT = 'INVALID_CREATIVE_ROTATION_TYPE_FOR_MANUAL_WEIGHT';
    const CLICK_MACROS_REQUIRED = 'CLICK_MACROS_REQUIRED';
    const VIEW_MACROS_NOT_ALLOWED = 'VIEW_MACROS_NOT_ALLOWED';
    const UNKNOWN = 'UNKNOWN';


}
