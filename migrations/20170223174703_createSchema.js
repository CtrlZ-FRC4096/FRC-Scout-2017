
exports.up = function(knex, Promise) {

    return Promise.all([
      knex.schema.createTableIfNotExists ("competitions",(table) =>{
        table.increments().primary().unsigned();
        table.string("name").notNullable();
        table.boolean("current").notNullable().defaultTo(false);
        table.enum("leftSideAlliance",["red","blue"]);
      }),

      knex.schema.createTableIfNotExists ("teams",(table)=>{
        table.integer("teamNumber")
             .unsigned()
             .notNullable()
             .primary();
        table.string("name").notNullable();
      }),

      knex.schema.createTableIfNotExists ("matches",(table)=>{
        table.increments().primary().unsigned();
        table.integer("matchNumber").notNullable();
        table.integer("compID").notNullable().unsigned().references("id").inTable("competitions");
        table.integer("lastUpdated").unsigned().nullable();
        table.integer("lastExported").unsigned().nullable();
      }),

      knex.schema.createTableIfNotExists ("devices",(table)=>{
        table.increments().primary().unsigned();
        table.string("key").unique();
      }),

      knex.schema.createTableIfNotExists ("scouters",(table)=>{
        table.increments();
        table.string("name").unique();

      }),

      knex.schema.createTableIfNotExists ("teamMatches",(table)=>{
        table.increments().primary().unsigned()
        table.integer("matchID")
             .unsigned()
             .notNullable()
             .references("id")
             .inTable("matches");
         table.enum("side", ["red","blue"])
              .notNullable()
         table.integer("position").unsigned().notNullable();
         table.integer("teamNumber").unsigned().notNullable().references("teamNumber").inTable("teams");
         table.integer("deviceID").notNullable().unsigned().references("id").inTable("devices");
         table.boolean("collectionStarted").notNullable().defaultTo(false);
         table.boolean("collectionEnded").notNullable().defaultTo(false);
         table.integer("scouterID").notNullable().unsigned().references("id").inTable("scouters");
         table.boolean("postprocessed").notNullable().defaultTo(false);
         table.boolean("ready").notNullable().defaultTo(false);
        table.unique(['teamNumber', 'matchID'])
      }),

      knex.schema.createTableIfNotExists ("matchGears",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.enum("location",["left","right","center"]).notNullable();
        table.boolean("result").notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchBallFeeds",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.integer("delta").notNullable();
        table.enum("location",["overflow","returnFar","returnClose","boilerSideClose","boilerSideMiddle","boilerSideFar","loadingSideClose","loadingSideFar"]).notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchBallFeeds_preprocess",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.decimal("before").notNullable();
        table.decimal("after").notNullable();
        table.enum("location",["overflow","returnFar","returnClose","boilerSideClose","boilerSideMiddle","boilerSideFar","loadingSideClose","loadingSideFar"]).notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchGearFeeds",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.boolean("result").notNullable();
        table.enum("method",["dropped","ground"]).notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchShoots",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.decimal("coordX").notNullable();
        table.decimal("coordY").notNullable();
        table.boolean("highLow").notNullable();
        table.integer("scored").notNullable();
        table.integer("missed").notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchShoots_preprocess",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.decimal("coordX").notNullable();
        table.decimal("coordY").notNullable();
        table.integer("scored").nullable();
        table.integer("missed").nullable();
        table.decimal("before").nullable();
        table.decimal("after").nullable();
        table.decimal("accuracy").nullable();

      }),

      knex.schema.createTableIfNotExists ("matchClimbs",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.integer("orderID").unsigned().notNullable();
        table.enum("mode",["auto","tele"]).notNullable();
        table.unique(["teamMatchID","orderID"]);

        table.boolean("touchpad").notNullable();
        table.decimal("duration").notNullable();
        table.enum("location",["left","right","center"]).notNullable();

      }),

      knex.schema.createTableIfNotExists ("matchAutos",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.unique(["teamMatchID"]);

        table.boolean("crossedLine").notNullable();
      }),

      knex.schema.createTableIfNotExists ("matchRatings",(table)=>{
        table.increments().primary().unsigned();
        table.integer("teamMatchID").notNullable().unsigned().references("id").inTable("teamMatches")
        table.unique(["teamMatchID"]);

        table.integer("agility").notNullable();
        table.integer("shootAccuracy").notNullable();
        table.integer("shootSpeed").notNullable();
        table.integer("gearFeedAccuracy").notNullable();
        table.integer("gearFeedSpeed").notNullable();
        table.integer("ballFeedAccuracy").notNullable();
        table.integer("ballFeedSpeed").notNullable();
        table.integer("gearSpeed").notNullable();
        table.integer("driverSkill").notNullable();
        table.integer("defense").notNullable();
      }),

      knex.schema.createTableIfNotExists ("teamPit",(table)=>{
        table.integer("teamNumber").primary().unsigned().references("teamNumber").inTable("teams");
        table.boolean("climbs").notNullable();
        table.boolean("gears").notNullable();
        table.boolean("lowShoots").notNullable();
        table.boolean("highShoots").notNullable();
        table.boolean("groundFeedsBalls").notNullable();
        table.boolean("hoppers").notNullable();
        table.boolean("loadingLanesBalls").notNullable();
        table.boolean("groundFeedsGears").notNullable();
        table.boolean("loadingLanesGears").notNullable();
      }),

      knex.schema.createTableIfNotExists ("teamPitDrivetrains",(table)=>{
        table.integer("teamNumber").primary().unsigned().references("teamNumber").inTable("teams");
        table.string("type").notNullable();
      })

    ])

};

exports.down = function(knex, Promise) {
  Promise.all([
    knex.schema.dropTable("teamPitDrivetrains"),
    knex.schema.dropTable("teamPit"),
    knex.schema.dropTable("matchRatings"),
    knex.schema.dropTable("matchAutos"),
    knex.schema.dropTable("matchClimbs"),
    knex.schema.dropTable("matchShoots_preprocess"),
    knex.schema.dropTable("matchShoots"),
    knex.schema.dropTable("matchGearFeeds"),
    knex.schema.dropTable("matchBallFeeds_preprocess"),
    knex.schema.dropTable("matchBallFeeds"),
    knex.schema.dropTable("matchGears"),
    knex.schema.dropTable("teamMatches"),
    knex.schema.dropTable("scouters"),
    knex.schema.dropTable("devices"),
    knex.schema.dropTable("matches"),
    knex.schema.dropTable("teams"),
    knex.schema.dropTable("competitions")
  ])
};
