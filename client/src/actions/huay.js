import * as types from "./types";

export const loadingOverlayActive = (isActive) => ({
    type: types.LOADING_OVERLAY_ACTIVE,
    isActive
});

export const updateHuayListBank = (data) => ({
    type: types.UPDATE_HUAY_LIST_BANK,
    data
});

export const updateTransferMethod = (data) => ({
    type: types.UPDATE_TRANSFER_METHOD,
    data
});

export const updateContactUs = (data) => ({
    type: types.UPDATE_CONTACT_US,
    data
});

export const updateListBank = (data) => ({
    type: types.UPDATE_LIST_BANK,
    data
});