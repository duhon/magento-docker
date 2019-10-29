function get_domain(r) {
    return process.env.UPSTREAM_PORT;
}

function get_mage_run_type(r) {
    return process.env.MAGE_RUN_TYPE;
}

function get_mage_debug_show_args(r) {
    return process.env.MAGE_DEBUG_SHOW_ARGS;
}