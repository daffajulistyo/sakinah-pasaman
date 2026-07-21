/*
 Navicat Premium Dump SQL

 Source Server         : LOCALPOSTGRE
 Source Server Type    : PostgreSQL
 Source Server Version : 170002 (170002)
 Source Host           : localhost:5432
 Source Catalog        : sakipnah
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 170002 (170002)
 File Encoding         : 65001

 Date: 17/06/2026 15:18:16
*/


-- ----------------------------
-- Sequence structure for cascading_opd_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."cascading_opd_id_seq";
CREATE SEQUENCE "public"."cascading_opd_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for failed_jobs_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."failed_jobs_id_seq";
CREATE SEQUENCE "public"."failed_jobs_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for migrations_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."migrations_id_seq";
CREATE SEQUENCE "public"."migrations_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for personal_access_tokens_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."personal_access_tokens_id_seq";
CREATE SEQUENCE "public"."personal_access_tokens_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for actions
-- ----------------------------
DROP TABLE IF EXISTS "public"."actions";
CREATE TABLE "public"."actions" (
  "id" uuid NOT NULL,
  "module_id" uuid,
  "controller_id" uuid,
  "function_id" uuid,
  "action_path" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "ajax_only" bool NOT NULL DEFAULT false,
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for atasan_pegawai
-- ----------------------------
DROP TABLE IF EXISTS "public"."atasan_pegawai";
CREATE TABLE "public"."atasan_pegawai" (
  "id" uuid NOT NULL,
  "nip_pegawai" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "nip_atasan" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "jabatan_atasan" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "unit_kerja_atasan" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "nama_atasan" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for cascading
-- ----------------------------
DROP TABLE IF EXISTS "public"."cascading";
CREATE TABLE "public"."cascading" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "id_program" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "kode_program" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "nama_program" text COLLATE "pg_catalog"."default",
  "id_skpd" int4 NOT NULL DEFAULT 0,
  "tahun" int4 NOT NULL DEFAULT 0,
  "pagu" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for cascading_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."cascading_opd";
CREATE TABLE "public"."cascading_opd" (
  "id" int4 NOT NULL DEFAULT nextval('cascading_opd_id_seq'::regclass),
  "sasaran_opd_id" uuid,
  "master_opd_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "id_program" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "kode_program" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "nama_program" text COLLATE "pg_catalog"."default",
  "id_skpd" int4 NOT NULL DEFAULT 0,
  "tahun" int4 NOT NULL DEFAULT 0,
  "pagu" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for controllers
-- ----------------------------
DROP TABLE IF EXISTS "public"."controllers";
CREATE TABLE "public"."controllers" (
  "id" uuid NOT NULL,
  "controller_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "controller_desc" text COLLATE "pg_catalog"."default",
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for data_hambatan
-- ----------------------------
DROP TABLE IF EXISTS "public"."data_hambatan";
CREATE TABLE "public"."data_hambatan" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid,
  "sasaran_opd_id" uuid NOT NULL,
  "hambatan" varchar(255) COLLATE "pg_catalog"."default",
  "tindak_lanjut" varchar(255) COLLATE "pg_catalog"."default",
  "tahun" int4 NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS "public"."failed_jobs";
CREATE TABLE "public"."failed_jobs" (
  "id" int8 NOT NULL DEFAULT nextval('failed_jobs_id_seq'::regclass),
  "uuid" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "connection" text COLLATE "pg_catalog"."default" NOT NULL,
  "queue" text COLLATE "pg_catalog"."default" NOT NULL,
  "payload" text COLLATE "pg_catalog"."default" NOT NULL,
  "exception" text COLLATE "pg_catalog"."default" NOT NULL,
  "failed_at" timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
)
;

-- ----------------------------
-- Table structure for functions
-- ----------------------------
DROP TABLE IF EXISTS "public"."functions";
CREATE TABLE "public"."functions" (
  "id" uuid NOT NULL,
  "function_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "function_desc" text COLLATE "pg_catalog"."default",
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for indikator_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."indikator_opd";
CREATE TABLE "public"."indikator_opd" (
  "id" uuid NOT NULL,
  "tujuan_opd_id" uuid,
  "sasaran_opd_id" uuid,
  "master_opd_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "indikator" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "defenisi" text COLLATE "pg_catalog"."default",
  "kegunaan" text COLLATE "pg_catalog"."default",
  "rilis" text COLLATE "pg_catalog"."default",
  "sumber_data" text COLLATE "pg_catalog"."default",
  "formula_perhitungan" text COLLATE "pg_catalog"."default",
  "satuan_id" uuid,
  "baseline" text COLLATE "pg_catalog"."default",
  "target_1" text COLLATE "pg_catalog"."default",
  "target_2" text COLLATE "pg_catalog"."default",
  "target_3" text COLLATE "pg_catalog"."default",
  "target_4" text COLLATE "pg_catalog"."default",
  "target_5" text COLLATE "pg_catalog"."default",
  "target_6" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT false,
  "is_indikator_kinerja_utama" bool NOT NULL DEFAULT false,
  "is_tujuan" bool NOT NULL DEFAULT false,
  "pohon_kinerja_visi_id" uuid NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "realisasi_1" text COLLATE "pg_catalog"."default",
  "realisasi_2" text COLLATE "pg_catalog"."default",
  "realisasi_3" text COLLATE "pg_catalog"."default",
  "realisasi_4" text COLLATE "pg_catalog"."default",
  "realisasi_5" text COLLATE "pg_catalog"."default",
  "realisasi_6" text COLLATE "pg_catalog"."default",
  "capaian_1" text COLLATE "pg_catalog"."default",
  "capaian_2" text COLLATE "pg_catalog"."default",
  "capaian_3" text COLLATE "pg_catalog"."default",
  "capaian_4" text COLLATE "pg_catalog"."default",
  "capaian_5" text COLLATE "pg_catalog"."default",
  "capaian_6" text COLLATE "pg_catalog"."default",
  "diampu_tim" bool NOT NULL DEFAULT false
)
;

-- ----------------------------
-- Table structure for log_access
-- ----------------------------
DROP TABLE IF EXISTS "public"."log_access";
CREATE TABLE "public"."log_access" (
  "id" uuid NOT NULL,
  "user" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "ip_address" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "user_agent" text COLLATE "pg_catalog"."default" NOT NULL,
  "unix_time" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for master_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."master_opd";
CREATE TABLE "public"."master_opd" (
  "id" uuid NOT NULL,
  "kode_opd" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "simpeg_opd_id" int4,
  "opd_unit_id" text COLLATE "pg_catalog"."default",
  "opd_unit" text COLLATE "pg_catalog"."default",
  "nama_opd" text COLLATE "pg_catalog"."default",
  "alamat" varchar(255) COLLATE "pg_catalog"."default",
  "telp" varchar(255) COLLATE "pg_catalog"."default",
  "website" varchar(255) COLLATE "pg_catalog"."default",
  "email" varchar(255) COLLATE "pg_catalog"."default",
  "ikd_opd_id" int4,
  "order" int4 NOT NULL DEFAULT 0,
  "parent_id" int4 NOT NULL DEFAULT 0,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "alias_opd" text COLLATE "pg_catalog"."default",
  "simonev_opd_id" int4,
  "kode_sub_opd" text COLLATE "pg_catalog"."default",
  "nama_sub_opd" text COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for master_satuan
-- ----------------------------
DROP TABLE IF EXISTS "public"."master_satuan";
CREATE TABLE "public"."master_satuan" (
  "id" uuid NOT NULL,
  "satuan" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "keterangan" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for menugroups
-- ----------------------------
DROP TABLE IF EXISTS "public"."menugroups";
CREATE TABLE "public"."menugroups" (
  "id" uuid NOT NULL,
  "menugroup_label" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "menugroup_desc" text COLLATE "pg_catalog"."default",
  "menugroup_order" int4 NOT NULL DEFAULT 0,
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS "public"."menus";
CREATE TABLE "public"."menus" (
  "id" uuid NOT NULL,
  "menugroup_id" uuid,
  "menu_label" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "menu_icon" text COLLATE "pg_catalog"."default",
  "menu_desc" text COLLATE "pg_catalog"."default",
  "menu_order" int4 NOT NULL DEFAULT 0,
  "route" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "action_id" uuid,
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "public"."migrations";
CREATE TABLE "public"."migrations" (
  "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
  "migration" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "batch" int4 NOT NULL
)
;

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS "public"."modules";
CREATE TABLE "public"."modules" (
  "id" uuid NOT NULL,
  "module_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "module_desc" text COLLATE "pg_catalog"."default",
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for opd_pendukung_indikator
-- ----------------------------
DROP TABLE IF EXISTS "public"."opd_pendukung_indikator";
CREATE TABLE "public"."opd_pendukung_indikator" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid,
  "pohon_kinerja_indikator_id" uuid,
  "master_opd_id" uuid,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS "public"."password_reset_tokens";
CREATE TABLE "public"."password_reset_tokens" (
  "email" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "token" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pengampu_indikator_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."pengampu_indikator_opd";
CREATE TABLE "public"."pengampu_indikator_opd" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "nip" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "nama" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "jns_jbtn_id" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "jns_jbtn_nm" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "jabatan_id" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "jabatan_nm" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "eselon_id" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "eselon_nm" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "is_ketua" bool NOT NULL DEFAULT false
)
;

-- ----------------------------
-- Table structure for perjanjian_kinerja
-- ----------------------------
DROP TABLE IF EXISTS "public"."perjanjian_kinerja";
CREATE TABLE "public"."perjanjian_kinerja" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "pohon_kinerja_indikator_id" uuid NOT NULL,
  "tahun" int4 NOT NULL,
  "target" text COLLATE "pg_catalog"."default",
  "murni" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for perjanjian_kinerja_program
-- ----------------------------
DROP TABLE IF EXISTS "public"."perjanjian_kinerja_program";
CREATE TABLE "public"."perjanjian_kinerja_program" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "tahun" int4 NOT NULL,
  "list_program" json NOT NULL,
  "anggaran" numeric NOT NULL,
  "murni" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS "public"."permissions";
CREATE TABLE "public"."permissions" (
  "id" uuid NOT NULL,
  "role_id" uuid,
  "action_id" uuid,
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS "public"."personal_access_tokens";
CREATE TABLE "public"."personal_access_tokens" (
  "id" int8 NOT NULL DEFAULT nextval('personal_access_tokens_id_seq'::regclass),
  "tokenable_type" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "tokenable_id" int8 NOT NULL,
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "token" varchar(64) COLLATE "pg_catalog"."default" NOT NULL,
  "abilities" text COLLATE "pg_catalog"."default",
  "last_used_at" timestamp(0),
  "expires_at" timestamp(0),
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pk_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."pk_opd";
CREATE TABLE "public"."pk_opd" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "eselon" text COLLATE "pg_catalog"."default",
  "tahun" int4 NOT NULL,
  "target" text COLLATE "pg_catalog"."default",
  "murni" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "created_by" varchar(255) COLLATE "pg_catalog"."default",
  "modified_by" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for pk_opd_program
-- ----------------------------
DROP TABLE IF EXISTS "public"."pk_opd_program";
CREATE TABLE "public"."pk_opd_program" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "tahun" int4 NOT NULL,
  "list_program" json NOT NULL,
  "anggaran" numeric NOT NULL,
  "murni" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT true,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pohon_kinerja_indikator
-- ----------------------------
DROP TABLE IF EXISTS "public"."pohon_kinerja_indikator";
CREATE TABLE "public"."pohon_kinerja_indikator" (
  "id" uuid NOT NULL,
  "pohon_kinerja_tujuan_id" uuid,
  "pohon_kinerja_sasaran_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "indikator" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "defenisi" text COLLATE "pg_catalog"."default",
  "kegunaan" text COLLATE "pg_catalog"."default",
  "pohon_kinerja_satuan_id" uuid,
  "baseline" text COLLATE "pg_catalog"."default",
  "target_1" text COLLATE "pg_catalog"."default",
  "target_2" text COLLATE "pg_catalog"."default",
  "target_3" text COLLATE "pg_catalog"."default",
  "target_4" text COLLATE "pg_catalog"."default",
  "target_5" text COLLATE "pg_catalog"."default",
  "target_6" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT false,
  "is_indikator_kinerja_utama" bool NOT NULL DEFAULT true,
  "is_tujuan" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "rilis" text COLLATE "pg_catalog"."default",
  "sumber_data" text COLLATE "pg_catalog"."default",
  "formula_perhitungan" text COLLATE "pg_catalog"."default",
  "satuan_id" uuid,
  "realisasi_1" text COLLATE "pg_catalog"."default",
  "realisasi_2" text COLLATE "pg_catalog"."default",
  "realisasi_3" text COLLATE "pg_catalog"."default",
  "realisasi_4" text COLLATE "pg_catalog"."default",
  "realisasi_5" text COLLATE "pg_catalog"."default",
  "realisasi_6" text COLLATE "pg_catalog"."default",
  "capaian_1" text COLLATE "pg_catalog"."default",
  "capaian_2" text COLLATE "pg_catalog"."default",
  "capaian_3" text COLLATE "pg_catalog"."default",
  "capaian_4" text COLLATE "pg_catalog"."default",
  "capaian_5" text COLLATE "pg_catalog"."default",
  "capaian_6" text COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for pohon_kinerja_misi
-- ----------------------------
DROP TABLE IF EXISTS "public"."pohon_kinerja_misi";
CREATE TABLE "public"."pohon_kinerja_misi" (
  "id" uuid NOT NULL,
  "pohon_kinerja_visi_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "misi" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pohon_kinerja_sasaran
-- ----------------------------
DROP TABLE IF EXISTS "public"."pohon_kinerja_sasaran";
CREATE TABLE "public"."pohon_kinerja_sasaran" (
  "id" uuid NOT NULL,
  "pohon_kinerja_tujuan_id" uuid,
  "parent_id" int4 NOT NULL DEFAULT 0,
  "order" int4 NOT NULL DEFAULT 0,
  "sasaran" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pohon_kinerja_tujuan
-- ----------------------------
DROP TABLE IF EXISTS "public"."pohon_kinerja_tujuan";
CREATE TABLE "public"."pohon_kinerja_tujuan" (
  "id" uuid NOT NULL,
  "pohon_kinerja_misi_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "tujuan" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for pohon_kinerja_visi
-- ----------------------------
DROP TABLE IF EXISTS "public"."pohon_kinerja_visi";
CREATE TABLE "public"."pohon_kinerja_visi" (
  "id" uuid NOT NULL,
  "period_starts" int4 NOT NULL DEFAULT 0,
  "period_ends" int4 NOT NULL DEFAULT 0,
  "visi" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for ref_indikator_operasional
-- ----------------------------
DROP TABLE IF EXISTS "public"."ref_indikator_operasional";
CREATE TABLE "public"."ref_indikator_operasional" (
  "id" uuid NOT NULL,
  "ref_sasaran_operasional_id" uuid NOT NULL,
  "indikator" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for ref_sasaran_operasional
-- ----------------------------
DROP TABLE IF EXISTS "public"."ref_sasaran_operasional";
CREATE TABLE "public"."ref_sasaran_operasional" (
  "id" uuid NOT NULL,
  "sasaran" varchar(255) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying,
  "jenis" varchar(255) COLLATE "pg_catalog"."default" DEFAULT NULL::character varying
)
;

-- ----------------------------
-- Table structure for rencana_aksi
-- ----------------------------
DROP TABLE IF EXISTS "public"."rencana_aksi";
CREATE TABLE "public"."rencana_aksi" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "pohon_kinerja_indikator_id" uuid NOT NULL,
  "target_tw1" text COLLATE "pg_catalog"."default",
  "target_tw2" text COLLATE "pg_catalog"."default",
  "target_tw3" text COLLATE "pg_catalog"."default",
  "target_tw4" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" text COLLATE "pg_catalog"."default",
  "realisasi_tw2" text COLLATE "pg_catalog"."default",
  "realisasi_tw3" text COLLATE "pg_catalog"."default",
  "realisasi_tw4" text COLLATE "pg_catalog"."default",
  "capaian_tw1" text COLLATE "pg_catalog"."default",
  "capaian_tw2" text COLLATE "pg_catalog"."default",
  "capaian_tw3" text COLLATE "pg_catalog"."default",
  "capaian_tw4" text COLLATE "pg_catalog"."default",
  "tahun" int4 NOT NULL,
  "is_active" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "hambatan" text COLLATE "pg_catalog"."default",
  "tindak_lanjut" text COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for rencana_aksi_langkah
-- ----------------------------
DROP TABLE IF EXISTS "public"."rencana_aksi_langkah";
CREATE TABLE "public"."rencana_aksi_langkah" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "pohon_kinerja_indikator_id" uuid NOT NULL,
  "langkah" text COLLATE "pg_catalog"."default" NOT NULL,
  "tahun" int4 NOT NULL,
  "target_tw1" text COLLATE "pg_catalog"."default",
  "target_tw2" text COLLATE "pg_catalog"."default",
  "target_tw3" text COLLATE "pg_catalog"."default",
  "target_tw4" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" text COLLATE "pg_catalog"."default",
  "realisasi_tw2" text COLLATE "pg_catalog"."default",
  "realisasi_tw3" text COLLATE "pg_catalog"."default",
  "realisasi_tw4" text COLLATE "pg_catalog"."default",
  "capaian_tw1" text COLLATE "pg_catalog"."default",
  "capaian_tw2" text COLLATE "pg_catalog"."default",
  "capaian_tw3" text COLLATE "pg_catalog"."default",
  "capaian_tw4" text COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "updated_by" char(100) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for rencana_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."rencana_opd";
CREATE TABLE "public"."rencana_opd" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "target_tw1" text COLLATE "pg_catalog"."default",
  "target_tw2" text COLLATE "pg_catalog"."default",
  "target_tw3" text COLLATE "pg_catalog"."default",
  "target_tw4" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" text COLLATE "pg_catalog"."default",
  "realisasi_tw2" text COLLATE "pg_catalog"."default",
  "realisasi_tw3" text COLLATE "pg_catalog"."default",
  "realisasi_tw4" text COLLATE "pg_catalog"."default",
  "capaian_tw1" text COLLATE "pg_catalog"."default",
  "capaian_tw2" text COLLATE "pg_catalog"."default",
  "capaian_tw3" text COLLATE "pg_catalog"."default",
  "capaian_tw4" text COLLATE "pg_catalog"."default",
  "tahun" int4 NOT NULL,
  "murni" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT true,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "hambatan" text COLLATE "pg_catalog"."default",
  "tindak_lanjut" text COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for rencana_opd_langkah
-- ----------------------------
DROP TABLE IF EXISTS "public"."rencana_opd_langkah";
CREATE TABLE "public"."rencana_opd_langkah" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "langkah" text COLLATE "pg_catalog"."default" NOT NULL,
  "tahun" int4 NOT NULL,
  "target_tw1" text COLLATE "pg_catalog"."default",
  "target_tw2" text COLLATE "pg_catalog"."default",
  "target_tw3" text COLLATE "pg_catalog"."default",
  "target_tw4" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" text COLLATE "pg_catalog"."default",
  "realisasi_tw2" text COLLATE "pg_catalog"."default",
  "realisasi_tw3" text COLLATE "pg_catalog"."default",
  "realisasi_tw4" text COLLATE "pg_catalog"."default",
  "capaian_tw1" text COLLATE "pg_catalog"."default",
  "capaian_tw2" text COLLATE "pg_catalog"."default",
  "capaian_tw3" text COLLATE "pg_catalog"."default",
  "capaian_tw4" text COLLATE "pg_catalog"."default",
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "satuan" text COLLATE "pg_catalog"."default",
  "keterangan" text COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for renja
-- ----------------------------
DROP TABLE IF EXISTS "public"."renja";
CREATE TABLE "public"."renja" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "tahun" int4 NOT NULL,
  "target" text COLLATE "pg_catalog"."default",
  "murni" bool NOT NULL DEFAULT false,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for renja_program
-- ----------------------------
DROP TABLE IF EXISTS "public"."renja_program";
CREATE TABLE "public"."renja_program" (
  "id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "list_program" json NOT NULL,
  "tahun" int4 NOT NULL,
  "anggaran" numeric NOT NULL,
  "is_active" bool NOT NULL DEFAULT true,
  "murni" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for rkpd
-- ----------------------------
DROP TABLE IF EXISTS "public"."rkpd";
CREATE TABLE "public"."rkpd" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "pohon_kinerja_indikator_id" uuid NOT NULL,
  "tahun" int4 NOT NULL,
  "target" text COLLATE "pg_catalog"."default",
  "murni" bool NOT NULL DEFAULT false,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for rkpd_kegiatan
-- ----------------------------
DROP TABLE IF EXISTS "public"."rkpd_kegiatan";
CREATE TABLE "public"."rkpd_kegiatan" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid NOT NULL,
  "list_kegiatan" json NOT NULL,
  "tahun" int4 NOT NULL,
  "is_active" bool NOT NULL DEFAULT true,
  "murni" bool NOT NULL DEFAULT true,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "anggaran" int8 NOT NULL DEFAULT 0
)
;

-- ----------------------------
-- Table structure for roleplay
-- ----------------------------
DROP TABLE IF EXISTS "public"."roleplay";
CREATE TABLE "public"."roleplay" (
  "id" uuid NOT NULL,
  "user_id" uuid,
  "role_id" uuid,
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS "public"."roles";
CREATE TABLE "public"."roles" (
  "id" uuid NOT NULL,
  "role_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "role_desc" text COLLATE "pg_catalog"."default",
  "type" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for sasaran_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."sasaran_opd";
CREATE TABLE "public"."sasaran_opd" (
  "id" uuid NOT NULL,
  "tujuan_opd_id" uuid NOT NULL,
  "master_opd_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "parent_id" varchar(100) COLLATE "pg_catalog"."default" NOT NULL DEFAULT '0'::character varying,
  "sasaran" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT false,
  "level" int4 NOT NULL DEFAULT 0,
  "pohon_kinerja_visi_id" uuid NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "is_sasaran_operasional" bool DEFAULT false,
  "sasaran_operasional_id" uuid
)
;

-- ----------------------------
-- Table structure for skp_indikator
-- ----------------------------
DROP TABLE IF EXISTS "public"."skp_indikator";
CREATE TABLE "public"."skp_indikator" (
  "id" uuid NOT NULL,
  "skp_id" uuid NOT NULL,
  "sasaran_opd_id" uuid NOT NULL,
  "indikator_opd_id" uuid NOT NULL,
  "target" text COLLATE "pg_catalog"."default",
  "satuan" text COLLATE "pg_catalog"."default",
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0),
  "hambatan" text COLLATE "pg_catalog"."default",
  "tindak_lanjut" varchar(255) COLLATE "pg_catalog"."default",
  "realisasi" varchar(255) COLLATE "pg_catalog"."default",
  "capaian" numeric(255,0),
  "target_tw1" text COLLATE "pg_catalog"."default",
  "target_tw2" text COLLATE "pg_catalog"."default",
  "target_tw3" text COLLATE "pg_catalog"."default",
  "target_tw4" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" varchar(255) COLLATE "pg_catalog"."default",
  "realisasi_tw2" varchar(255) COLLATE "pg_catalog"."default",
  "realisasi_tw3" varchar(255) COLLATE "pg_catalog"."default",
  "realisasi_tw4" varchar(255) COLLATE "pg_catalog"."default",
  "capaian_tw1" numeric(255,0),
  "capaian_tw2" numeric(255,0),
  "capaian_tw3" numeric(255,0),
  "capaian_tw4" numeric(255,0) DEFAULT NULL::numeric
)
;

-- ----------------------------
-- Table structure for skp_langkah
-- ----------------------------
DROP TABLE IF EXISTS "public"."skp_langkah";
CREATE TABLE "public"."skp_langkah" (
  "id" uuid NOT NULL,
  "skp_id" uuid NOT NULL,
  "indikator_skp_id" uuid NOT NULL,
  "langkah" text COLLATE "pg_catalog"."default" NOT NULL,
  "target_tw1" text COLLATE "pg_catalog"."default" NOT NULL,
  "target_tw2" text COLLATE "pg_catalog"."default" NOT NULL,
  "target_tw3" text COLLATE "pg_catalog"."default" NOT NULL,
  "target_tw4" text COLLATE "pg_catalog"."default" NOT NULL,
  "satuan" text COLLATE "pg_catalog"."default",
  "keterangan" text COLLATE "pg_catalog"."default",
  "realisasi_tw1" text COLLATE "pg_catalog"."default",
  "realisasi_tw2" text COLLATE "pg_catalog"."default",
  "realisasi_tw3" text COLLATE "pg_catalog"."default",
  "realisasi_tw4" text COLLATE "pg_catalog"."default",
  "capaian_tw1" text COLLATE "pg_catalog"."default",
  "capaian_tw2" text COLLATE "pg_catalog"."default",
  "capaian_tw3" text COLLATE "pg_catalog"."default",
  "capaian_tw4" text COLLATE "pg_catalog"."default",
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for skp_periode
-- ----------------------------
DROP TABLE IF EXISTS "public"."skp_periode";
CREATE TABLE "public"."skp_periode" (
  "id" uuid NOT NULL,
  "nip" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "master_opd_id" uuid NOT NULL,
  "periode_awal" date NOT NULL,
  "periode_akhir" date NOT NULL,
  "tahun" int4 NOT NULL,
  "pendekatan" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "is_active" bool NOT NULL DEFAULT true,
  "batas_input" date,
  "jns_jbtn_id" text COLLATE "pg_catalog"."default",
  "jns_jbtn_nm" text COLLATE "pg_catalog"."default",
  "jabatan_id" text COLLATE "pg_catalog"."default",
  "jabatan_nm" text COLLATE "pg_catalog"."default",
  "eselon_id" text COLLATE "pg_catalog"."default",
  "eselon_nm" text COLLATE "pg_catalog"."default",
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for tujuan_opd
-- ----------------------------
DROP TABLE IF EXISTS "public"."tujuan_opd";
CREATE TABLE "public"."tujuan_opd" (
  "id" uuid NOT NULL,
  "pohon_kinerja_sasaran_id" uuid,
  "master_opd_id" uuid,
  "order" int4 NOT NULL DEFAULT 0,
  "tujuan" text COLLATE "pg_catalog"."default" NOT NULL,
  "is_direct" bool NOT NULL DEFAULT false,
  "is_active" bool NOT NULL DEFAULT false,
  "pohon_kinerja_visi_id" uuid NOT NULL,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for user_sakip
-- ----------------------------
DROP TABLE IF EXISTS "public"."user_sakip";
CREATE TABLE "public"."user_sakip" (
  "id" uuid NOT NULL,
  "user_id" uuid,
  "master_opd_id" uuid,
  "created_by" varchar(100) COLLATE "pg_catalog"."default",
  "updated_by" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for user_simpeg
-- ----------------------------
DROP TABLE IF EXISTS "public"."user_simpeg";
CREATE TABLE "public"."user_simpeg" (
  "id" uuid NOT NULL,
  "user_id" uuid,
  "master_opd_id" uuid,
  "nip" varchar(100) COLLATE "pg_catalog"."default",
  "opd_id" varchar(100) COLLATE "pg_catalog"."default",
  "opd_nm" varchar(100) COLLATE "pg_catalog"."default",
  "sub_opd_id" varchar(100) COLLATE "pg_catalog"."default",
  "sub_opd_nm" varchar(100) COLLATE "pg_catalog"."default",
  "jns_jbtn_id" varchar(100) COLLATE "pg_catalog"."default",
  "jns_jbtn_nm" varchar(100) COLLATE "pg_catalog"."default",
  "jabatan_id" varchar(100) COLLATE "pg_catalog"."default",
  "jabatan_nm" varchar(100) COLLATE "pg_catalog"."default",
  "eselon_id" varchar(100) COLLATE "pg_catalog"."default",
  "eselon_nm" varchar(100) COLLATE "pg_catalog"."default",
  "json_pegawai" text COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL,
  "created_at" timestamp(0),
  "updated_at" timestamp(0),
  "deleted_at" timestamp(0)
)
;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
  "id" uuid NOT NULL,
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "username" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "email_verified_at" timestamp(0),
  "password" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "current_role" varchar(100) COLLATE "pg_catalog"."default",
  "is_active" bool NOT NULL DEFAULT true,
  "remember_token" varchar(100) COLLATE "pg_catalog"."default",
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."cascading_opd_id_seq"
OWNED BY "public"."cascading_opd"."id";
SELECT setval('"public"."cascading_opd_id_seq"', 288, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."failed_jobs_id_seq"
OWNED BY "public"."failed_jobs"."id";
SELECT setval('"public"."failed_jobs_id_seq"', 1, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."migrations_id_seq"
OWNED BY "public"."migrations"."id";
SELECT setval('"public"."migrations_id_seq"', 124, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."personal_access_tokens_id_seq"
OWNED BY "public"."personal_access_tokens"."id";
SELECT setval('"public"."personal_access_tokens_id_seq"', 1, false);

-- ----------------------------
-- Primary Key structure for table actions
-- ----------------------------
ALTER TABLE "public"."actions" ADD CONSTRAINT "actions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table atasan_pegawai
-- ----------------------------
ALTER TABLE "public"."atasan_pegawai" ADD CONSTRAINT "atasan_pegawai_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table cascading
-- ----------------------------
ALTER TABLE "public"."cascading" ADD CONSTRAINT "cascading_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table cascading_opd
-- ----------------------------
ALTER TABLE "public"."cascading_opd" ADD CONSTRAINT "cascading_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table controllers
-- ----------------------------
ALTER TABLE "public"."controllers" ADD CONSTRAINT "controllers_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table data_hambatan
-- ----------------------------
ALTER TABLE "public"."data_hambatan" ADD CONSTRAINT "data_hambatan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table failed_jobs
-- ----------------------------
ALTER TABLE "public"."failed_jobs" ADD CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid");

-- ----------------------------
-- Primary Key structure for table failed_jobs
-- ----------------------------
ALTER TABLE "public"."failed_jobs" ADD CONSTRAINT "failed_jobs_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table functions
-- ----------------------------
ALTER TABLE "public"."functions" ADD CONSTRAINT "functions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table indikator_opd
-- ----------------------------
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table log_access
-- ----------------------------
ALTER TABLE "public"."log_access" ADD CONSTRAINT "log_access_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table master_opd
-- ----------------------------
ALTER TABLE "public"."master_opd" ADD CONSTRAINT "master_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table master_satuan
-- ----------------------------
ALTER TABLE "public"."master_satuan" ADD CONSTRAINT "master_satuan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table menugroups
-- ----------------------------
ALTER TABLE "public"."menugroups" ADD CONSTRAINT "menugroups_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table menus
-- ----------------------------
ALTER TABLE "public"."menus" ADD CONSTRAINT "menus_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table migrations
-- ----------------------------
ALTER TABLE "public"."migrations" ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table modules
-- ----------------------------
ALTER TABLE "public"."modules" ADD CONSTRAINT "modules_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table opd_pendukung_indikator
-- ----------------------------
ALTER TABLE "public"."opd_pendukung_indikator" ADD CONSTRAINT "opd_pendukung_indikator_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table password_reset_tokens
-- ----------------------------
ALTER TABLE "public"."password_reset_tokens" ADD CONSTRAINT "password_reset_tokens_pkey" PRIMARY KEY ("email");

-- ----------------------------
-- Primary Key structure for table pengampu_indikator_opd
-- ----------------------------
ALTER TABLE "public"."pengampu_indikator_opd" ADD CONSTRAINT "pengampu_indikator_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table perjanjian_kinerja
-- ----------------------------
ALTER TABLE "public"."perjanjian_kinerja" ADD CONSTRAINT "perjanjian_kinerja_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table perjanjian_kinerja_program
-- ----------------------------
ALTER TABLE "public"."perjanjian_kinerja_program" ADD CONSTRAINT "perjanjian_kinerja_program_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table permissions
-- ----------------------------
ALTER TABLE "public"."permissions" ADD CONSTRAINT "permissions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table personal_access_tokens
-- ----------------------------
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "public"."personal_access_tokens" USING btree (
  "tokenable_type" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST,
  "tokenable_id" "pg_catalog"."int8_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table personal_access_tokens
-- ----------------------------
ALTER TABLE "public"."personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_token_unique" UNIQUE ("token");

-- ----------------------------
-- Primary Key structure for table personal_access_tokens
-- ----------------------------
ALTER TABLE "public"."personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pk_opd
-- ----------------------------
ALTER TABLE "public"."pk_opd" ADD CONSTRAINT "pk_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pk_opd_program
-- ----------------------------
ALTER TABLE "public"."pk_opd_program" ADD CONSTRAINT "pk_opd_program_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pohon_kinerja_indikator
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_indikator" ADD CONSTRAINT "pohon_kinerja_indikator_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pohon_kinerja_misi
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_misi" ADD CONSTRAINT "pohon_kinerja_misi_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pohon_kinerja_sasaran
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_sasaran" ADD CONSTRAINT "pohon_kinerja_sasaran_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pohon_kinerja_tujuan
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_tujuan" ADD CONSTRAINT "pohon_kinerja_tujuan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table pohon_kinerja_visi
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_visi" ADD CONSTRAINT "pohon_kinerja_visi_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table ref_indikator_operasional
-- ----------------------------
ALTER TABLE "public"."ref_indikator_operasional" ADD CONSTRAINT "ref_indikator_operasional_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rencana_aksi
-- ----------------------------
ALTER TABLE "public"."rencana_aksi" ADD CONSTRAINT "rencana_aksi_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rencana_aksi_langkah
-- ----------------------------
ALTER TABLE "public"."rencana_aksi_langkah" ADD CONSTRAINT "rencana_aksi_langkah_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rencana_opd
-- ----------------------------
ALTER TABLE "public"."rencana_opd" ADD CONSTRAINT "rencana_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rencana_opd_langkah
-- ----------------------------
ALTER TABLE "public"."rencana_opd_langkah" ADD CONSTRAINT "rencana_opd_langkah_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table renja
-- ----------------------------
ALTER TABLE "public"."renja" ADD CONSTRAINT "renja_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table renja_program
-- ----------------------------
ALTER TABLE "public"."renja_program" ADD CONSTRAINT "renja_program_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rkpd
-- ----------------------------
ALTER TABLE "public"."rkpd" ADD CONSTRAINT "rkpd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rkpd_kegiatan
-- ----------------------------
ALTER TABLE "public"."rkpd_kegiatan" ADD CONSTRAINT "rkpd_kegiatan_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table roleplay
-- ----------------------------
ALTER TABLE "public"."roleplay" ADD CONSTRAINT "roleplay_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table roles
-- ----------------------------
ALTER TABLE "public"."roles" ADD CONSTRAINT "roles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table sasaran_opd
-- ----------------------------
ALTER TABLE "public"."sasaran_opd" ADD CONSTRAINT "sasaran_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table skp_indikator
-- ----------------------------
ALTER TABLE "public"."skp_indikator" ADD CONSTRAINT "skp_indikator_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table skp_langkah
-- ----------------------------
ALTER TABLE "public"."skp_langkah" ADD CONSTRAINT "skp_langkah_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table skp_periode
-- ----------------------------
ALTER TABLE "public"."skp_periode" ADD CONSTRAINT "skp_periode_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table tujuan_opd
-- ----------------------------
ALTER TABLE "public"."tujuan_opd" ADD CONSTRAINT "tujuan_opd_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table user_sakip
-- ----------------------------
ALTER TABLE "public"."user_sakip" ADD CONSTRAINT "user_sakip_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table user_simpeg
-- ----------------------------
ALTER TABLE "public"."user_simpeg" ADD CONSTRAINT "user_simpeg_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_username_unique" UNIQUE ("username");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table actions
-- ----------------------------
ALTER TABLE "public"."actions" ADD CONSTRAINT "actions_controller_id_foreign" FOREIGN KEY ("controller_id") REFERENCES "public"."controllers" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."actions" ADD CONSTRAINT "actions_function_id_foreign" FOREIGN KEY ("function_id") REFERENCES "public"."functions" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."actions" ADD CONSTRAINT "actions_module_id_foreign" FOREIGN KEY ("module_id") REFERENCES "public"."modules" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table cascading
-- ----------------------------
ALTER TABLE "public"."cascading" ADD CONSTRAINT "cascading_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table cascading_opd
-- ----------------------------
ALTER TABLE "public"."cascading_opd" ADD CONSTRAINT "cascading_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."cascading_opd" ADD CONSTRAINT "cascading_opd_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table data_hambatan
-- ----------------------------
ALTER TABLE "public"."data_hambatan" ADD CONSTRAINT "data_hambatan_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."data_hambatan" ADD CONSTRAINT "data_hambatan_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table indikator_opd
-- ----------------------------
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_pohon_kinerja_visi_id_foreign" FOREIGN KEY ("pohon_kinerja_visi_id") REFERENCES "public"."pohon_kinerja_visi" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_satuan_id_foreign" FOREIGN KEY ("satuan_id") REFERENCES "public"."master_satuan" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."indikator_opd" ADD CONSTRAINT "indikator_opd_tujuan_opd_id_foreign" FOREIGN KEY ("tujuan_opd_id") REFERENCES "public"."tujuan_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table menus
-- ----------------------------
ALTER TABLE "public"."menus" ADD CONSTRAINT "menus_action_id_foreign" FOREIGN KEY ("action_id") REFERENCES "public"."actions" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."menus" ADD CONSTRAINT "menus_menugroup_id_foreign" FOREIGN KEY ("menugroup_id") REFERENCES "public"."menugroups" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table opd_pendukung_indikator
-- ----------------------------
ALTER TABLE "public"."opd_pendukung_indikator" ADD CONSTRAINT "opd_pendukung_indikator_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."opd_pendukung_indikator" ADD CONSTRAINT "opd_pendukung_indikator_pohon_kinerja_indikator_id_foreign" FOREIGN KEY ("pohon_kinerja_indikator_id") REFERENCES "public"."pohon_kinerja_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."opd_pendukung_indikator" ADD CONSTRAINT "opd_pendukung_indikator_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pengampu_indikator_opd
-- ----------------------------
ALTER TABLE "public"."pengampu_indikator_opd" ADD CONSTRAINT "pengampu_indikator_opd_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pengampu_indikator_opd" ADD CONSTRAINT "pengampu_indikator_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pengampu_indikator_opd" ADD CONSTRAINT "pengampu_indikator_opd_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table perjanjian_kinerja
-- ----------------------------
ALTER TABLE "public"."perjanjian_kinerja" ADD CONSTRAINT "perjanjian_kinerja_pohon_kinerja_indikator_id_foreign" FOREIGN KEY ("pohon_kinerja_indikator_id") REFERENCES "public"."pohon_kinerja_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."perjanjian_kinerja" ADD CONSTRAINT "perjanjian_kinerja_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table perjanjian_kinerja_program
-- ----------------------------
ALTER TABLE "public"."perjanjian_kinerja_program" ADD CONSTRAINT "perjanjian_kinerja_program_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table permissions
-- ----------------------------
ALTER TABLE "public"."permissions" ADD CONSTRAINT "permissions_action_id_foreign" FOREIGN KEY ("action_id") REFERENCES "public"."actions" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."permissions" ADD CONSTRAINT "permissions_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "public"."roles" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pk_opd
-- ----------------------------
ALTER TABLE "public"."pk_opd" ADD CONSTRAINT "pk_opd_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pk_opd" ADD CONSTRAINT "pk_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pk_opd" ADD CONSTRAINT "pk_opd_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pk_opd_program
-- ----------------------------
ALTER TABLE "public"."pk_opd_program" ADD CONSTRAINT "pk_opd_program_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pk_opd_program" ADD CONSTRAINT "pk_opd_program_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pohon_kinerja_indikator
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_indikator" ADD CONSTRAINT "pohon_kinerja_indikator_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pohon_kinerja_indikator" ADD CONSTRAINT "pohon_kinerja_indikator_pohon_kinerja_satuan_id_foreign" FOREIGN KEY ("pohon_kinerja_satuan_id") REFERENCES "public"."master_satuan" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."pohon_kinerja_indikator" ADD CONSTRAINT "pohon_kinerja_indikator_pohon_kinerja_tujuan_id_foreign" FOREIGN KEY ("pohon_kinerja_tujuan_id") REFERENCES "public"."pohon_kinerja_tujuan" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pohon_kinerja_misi
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_misi" ADD CONSTRAINT "pohon_kinerja_misi_pohon_kinerja_visi_id_foreign" FOREIGN KEY ("pohon_kinerja_visi_id") REFERENCES "public"."pohon_kinerja_visi" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pohon_kinerja_sasaran
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_sasaran" ADD CONSTRAINT "pohon_kinerja_sasaran_pohon_kinerja_tujuan_id_foreign" FOREIGN KEY ("pohon_kinerja_tujuan_id") REFERENCES "public"."pohon_kinerja_tujuan" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table pohon_kinerja_tujuan
-- ----------------------------
ALTER TABLE "public"."pohon_kinerja_tujuan" ADD CONSTRAINT "pohon_kinerja_tujuan_pohon_kinerja_misi_id_foreign" FOREIGN KEY ("pohon_kinerja_misi_id") REFERENCES "public"."pohon_kinerja_misi" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rencana_aksi
-- ----------------------------
ALTER TABLE "public"."rencana_aksi" ADD CONSTRAINT "rencana_aksi_pohon_kinerja_indikator_id_foreign" FOREIGN KEY ("pohon_kinerja_indikator_id") REFERENCES "public"."pohon_kinerja_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_aksi" ADD CONSTRAINT "rencana_aksi_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rencana_aksi_langkah
-- ----------------------------
ALTER TABLE "public"."rencana_aksi_langkah" ADD CONSTRAINT "rencana_aksi_langkah_pohon_kinerja_indikator_id_foreign" FOREIGN KEY ("pohon_kinerja_indikator_id") REFERENCES "public"."pohon_kinerja_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_aksi_langkah" ADD CONSTRAINT "rencana_aksi_langkah_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rencana_opd
-- ----------------------------
ALTER TABLE "public"."rencana_opd" ADD CONSTRAINT "rencana_opd_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_opd" ADD CONSTRAINT "rencana_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_opd" ADD CONSTRAINT "rencana_opd_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rencana_opd_langkah
-- ----------------------------
ALTER TABLE "public"."rencana_opd_langkah" ADD CONSTRAINT "rencana_opd_langkah_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_opd_langkah" ADD CONSTRAINT "rencana_opd_langkah_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rencana_opd_langkah" ADD CONSTRAINT "rencana_opd_langkah_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table renja
-- ----------------------------
ALTER TABLE "public"."renja" ADD CONSTRAINT "renja_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."renja" ADD CONSTRAINT "renja_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."renja" ADD CONSTRAINT "renja_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table renja_program
-- ----------------------------
ALTER TABLE "public"."renja_program" ADD CONSTRAINT "renja_program_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."renja_program" ADD CONSTRAINT "renja_program_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rkpd
-- ----------------------------
ALTER TABLE "public"."rkpd" ADD CONSTRAINT "rkpd_pohon_kinerja_indikator_id_foreign" FOREIGN KEY ("pohon_kinerja_indikator_id") REFERENCES "public"."pohon_kinerja_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."rkpd" ADD CONSTRAINT "rkpd_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table rkpd_kegiatan
-- ----------------------------
ALTER TABLE "public"."rkpd_kegiatan" ADD CONSTRAINT "rkpd_kegiatan_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table roleplay
-- ----------------------------
ALTER TABLE "public"."roleplay" ADD CONSTRAINT "roleplay_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "public"."roles" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."roleplay" ADD CONSTRAINT "roleplay_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table sasaran_opd
-- ----------------------------
ALTER TABLE "public"."sasaran_opd" ADD CONSTRAINT "sasaran_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."sasaran_opd" ADD CONSTRAINT "sasaran_opd_pohon_kinerja_visi_id_foreign" FOREIGN KEY ("pohon_kinerja_visi_id") REFERENCES "public"."pohon_kinerja_visi" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."sasaran_opd" ADD CONSTRAINT "sasaran_opd_tujuan_opd_id_foreign" FOREIGN KEY ("tujuan_opd_id") REFERENCES "public"."tujuan_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table skp_indikator
-- ----------------------------
ALTER TABLE "public"."skp_indikator" ADD CONSTRAINT "skp_indikator_indikator_opd_id_foreign" FOREIGN KEY ("indikator_opd_id") REFERENCES "public"."indikator_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."skp_indikator" ADD CONSTRAINT "skp_indikator_sasaran_opd_id_foreign" FOREIGN KEY ("sasaran_opd_id") REFERENCES "public"."sasaran_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."skp_indikator" ADD CONSTRAINT "skp_indikator_skp_id_foreign" FOREIGN KEY ("skp_id") REFERENCES "public"."skp_periode" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table skp_langkah
-- ----------------------------
ALTER TABLE "public"."skp_langkah" ADD CONSTRAINT "skp_langkah_indikator_skp_id_foreign" FOREIGN KEY ("indikator_skp_id") REFERENCES "public"."skp_indikator" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."skp_langkah" ADD CONSTRAINT "skp_langkah_skp_id_foreign" FOREIGN KEY ("skp_id") REFERENCES "public"."skp_periode" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table skp_periode
-- ----------------------------
ALTER TABLE "public"."skp_periode" ADD CONSTRAINT "skp_periode_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tujuan_opd
-- ----------------------------
ALTER TABLE "public"."tujuan_opd" ADD CONSTRAINT "tujuan_opd_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tujuan_opd" ADD CONSTRAINT "tujuan_opd_pohon_kinerja_sasaran_id_foreign" FOREIGN KEY ("pohon_kinerja_sasaran_id") REFERENCES "public"."pohon_kinerja_sasaran" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."tujuan_opd" ADD CONSTRAINT "tujuan_opd_pohon_kinerja_visi_id_foreign" FOREIGN KEY ("pohon_kinerja_visi_id") REFERENCES "public"."pohon_kinerja_visi" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table user_sakip
-- ----------------------------
ALTER TABLE "public"."user_sakip" ADD CONSTRAINT "user_sakip_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."user_sakip" ADD CONSTRAINT "user_sakip_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table user_simpeg
-- ----------------------------
ALTER TABLE "public"."user_simpeg" ADD CONSTRAINT "user_simpeg_master_opd_id_foreign" FOREIGN KEY ("master_opd_id") REFERENCES "public"."master_opd" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."user_simpeg" ADD CONSTRAINT "user_simpeg_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
