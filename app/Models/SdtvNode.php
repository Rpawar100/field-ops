<?php

namespace App\Models;

/**
 * SdtvNode is DEPRECATED — the 2024 schema uses separate tables:
 * states, districts, talukas, villages (not a single sdtv_nodes table).
 *
 * This class is kept for backward-compatibility references only.
 * All new code should use State, District, Taluka, Village models directly.
 *
 * @deprecated Use State, District, Taluka, Village models instead.
 */
class SdtvNode extends Village
{
    // Backward-compat alias — points at villages table as closest match
}
